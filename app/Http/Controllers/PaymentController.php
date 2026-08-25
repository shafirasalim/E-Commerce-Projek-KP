<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    // Konfigurasi Midtrans otomatis saat controller dipanggil
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);  // ← FIXED
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    /**
     * Tampilkan halaman pilih metode pembayaran
     */
    public function show($transactionId)
    {
        $transaction = Transaction::with('details.product')->findOrFail($transactionId);

        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak');
        }

        if ($transaction->status === 'paid') {
            return redirect()->route('orders.show', $transaction->id)->with('success', 'Transaksi ini sudah dibayar.');
        }

        return view('payment.show', compact('transaction'));
    }

    /**
     * Proses pembayaran (Minta Snap Token ke Midtrans)
     */
    public function process(Request $request, $transactionId)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:bank_transfer,ewallet',
        ]);

        $transaction = Transaction::with('details.product')->findOrFail($transactionId);

        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak');
        }

        $orderId = 'INV-' . $transaction->id . '-' . date('YmdHis');
        $baseUrl = config('app.url');

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name ?? 'Customer',
                'email' => $transaction->user->email ?? 'customer@example.com',
            ],
            'item_details' => [],
            'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'mandiri', 'bri_va', 'gopay', 'shopeepay'],
            'finish_redirect_url' => $baseUrl . '/payment/' . $transaction->id . '/success',
            'unfinish_redirect_url' => $baseUrl . '/my-orders',
            'error_redirect_url' => $baseUrl . '/my-orders',
        ];

        foreach ($transaction->details as $detail) {
            $params['item_details'][] = [
                'id' => $detail->product_id,
                'price' => (int) $detail->price,
                'quantity' => $detail->quantity,
                'name' => substr($detail->product->name ?? 'Produk', 0, 50),
            ];
        }

        try {
            $transactionResponse = Snap::createTransaction($params);

            $transaction->update([
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
            ]);

            return redirect()->route('payment.redirect', $transaction->id);

        } catch (\Throwable $e) {
            \Log::error('MIDTRANS PROCESS ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Halaman sukses setelah bayar
     */
    public function success($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payment.success', compact('transaction'));
    }

    /**
     * Halaman ketika user batal / belum menyelesaikan pembayaran
     */
    public function unfinish($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return redirect()->route('orders.show', $transaction->id)
            ->with('info', 'Pembayaran belum selesai. Silakan lanjutkan pembayaran kapan saja.');
    }

    /**
     * Webhook/Callback dari Midtrans (Otomatis update status)
     */
    public function notification(Request $request)
    {
        $notif = new Notification();

        $orderId = $notif->order_id;
        $transactionId = explode('-', $orderId)[1];
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transactionStatus = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraud == 'accept' || !$fraud) {
                $transaction->update(['status' => 'paid']);

                $cart = Cart::where('user_id', $transaction->user_id)->first();
                if ($cart) {
                    $cart->items()->delete();
                }
            }
        } elseif ($transactionStatus == 'pending') {
            $transaction->update(['status' => 'pending']);
        } elseif ($transactionStatus == 'deny') {
            $transaction->update(['status' => 'failed']);
        } elseif ($transactionStatus == 'expire') {
            $transaction->update(['status' => 'expired']);
        } elseif ($transactionStatus == 'cancel') {
            $transaction->update(['status' => 'cancelled']);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Buka popup Snap di dalam web kita (TANPA redirect keluar)
     */
    public function redirectToMidtrans($transactionId)
    {
        try {
            $transaction = Transaction::with('details.product')->findOrFail($transactionId);

            if ($transaction->user_id !== Auth::id()) {
                abort(403, 'Akses ditolak');
            }

            if ($transaction->status === 'paid') {
                return redirect()->route('orders.show', $transaction->id)
                    ->with('success', 'Transaksi ini sudah dibayar.');
            }

            if (!$transaction->total_amount || $transaction->total_amount <= 0) {
                return redirect()->route('orders.show', $transaction->id)
                    ->with('error', 'Terjadi kesalahan pada jumlah pembayaran.');
            }

            $orderId = 'INV-' . $transaction->id . '-' . date('YmdHis');

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $transaction->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $transaction->user->name ?? 'Customer',
                    'email' => $transaction->user->email ?? 'customer@example.com',
                ],
                'item_details' => [],
                'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'mandiri', 'bri_va', 'gopay', 'shopeepay', 'qris'],
            ];

            foreach ($transaction->details as $detail) {
                if ($detail->price <= 0 || $detail->quantity <= 0) {
                    continue;
                }

                $params['item_details'][] = [
                    'id' => $detail->product_id,
                    'price' => (int) $detail->price,
                    'quantity' => (int) $detail->quantity,
                    'name' => substr($detail->product->name ?? 'Produk', 0, 50),
                ];
            }

            $transactionResponse = Snap::createTransaction($params);

            $transaction->update([
                'status' => 'pending',
                'payment_method' => 'midtrans',
            ]);

            // Pilih snap.js sesuai environment
            $snapUrl = config('midtrans.is_production')
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js';

            return view('payment.pay', [
                'snapToken' => $transactionResponse->token,
                'snapUrl' => $snapUrl,
                'clientKey' => config('midtrans.client_key'),
                'successUrl' => route('payment.success', $transaction->id),
                'ordersUrl' => route('orders.index'),
            ]);

        } catch (\Throwable $e) {
            \Log::error('MIDTRANS ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}