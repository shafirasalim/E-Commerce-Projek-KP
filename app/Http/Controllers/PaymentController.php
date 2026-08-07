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
        Config::$isProduction = (bool) config('midtrans.is_production');
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

        // ORDER ID UNIK: Tambah timestamp biar gak bentrok
        $orderId = 'INV-' . $transaction->id . '-' . date('YmdHis');

        // URL absolut sesuai environment (local / Railway)
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

            // URL absolut buat redirect setelah bayar
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

        \Log::info('MIDTRANS CONFIG', [
            'server_key' => Config::$serverKey,
            'client_key' => config('midtrans.client_key'),
            'production' => Config::$isProduction,
            'app_url' => config('app.url'),
        ]);

        \Log::info('MIDTRANS PARAMS', $params);

        try {
            $transactionResponse = Snap::createTransaction($params);

            $redirectUrl = $transactionResponse->redirect_url;

            $transaction->update([
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
            ]);

            // Langsung redirect ke halaman Midtrans
            return redirect()->away($redirectUrl);

        } catch (\Throwable $e) {
            \Log::error('MIDTRANS PROCESS ERROR', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
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
        // Ambil ID transaksi dari order_id (format: INV-{id}-{timestamp})
        $transactionId = explode('-', $orderId)[1];
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transactionStatus = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        // CEK STATUS PEMBAYARAN
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraud == 'accept' || !$fraud) {
                $transaction->update(['status' => 'paid']);

                // === HAPUS CART KALAU SUKSES BAYAR ===
                $cart = Cart::where('user_id', $transaction->user_id)->first();
                if ($cart) {
                    $cart->items()->delete();
                }
                // =====================================
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
     * Langsung redirect ke Midtrans (tanpa pilih metode)
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

            // VALIDASI: Cek total amount
            if (!$transaction->total_amount || $transaction->total_amount <= 0) {
                \Log::error('Invalid transaction amount', [
                    'transaction_id' => $transaction->id,
                    'total_amount' => $transaction->total_amount,
                    'details' => $transaction->details->toArray(),
                ]);

                return redirect()->route('orders.show', $transaction->id)
                    ->with('error', 'Terjadi kesalahan pada jumlah pembayaran. Silakan hubungi administrator.');
            }

            // Order ID unik
            $orderId = 'INV-' . $transaction->id . '-' . date('YmdHis');

            // URL absolut sesuai environment (local / Railway)
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
                'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'mandiri', 'bri_va', 'gopay', 'shopeepay', 'qris'],

                // URL absolut buat redirect setelah bayar
                'finish_redirect_url' => $baseUrl . '/payment/' . $transaction->id . '/success',
                'unfinish_redirect_url' => $baseUrl . '/my-orders',
                'error_redirect_url' => $baseUrl . '/my-orders',
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

            \Log::info('MIDTRANS CONFIG', [
                'server_key' => Config::$serverKey,
                'client_key' => config('midtrans.client_key'),
                'production' => Config::$isProduction,
                'app_url' => config('app.url'),
            ]);

            \Log::info('MIDTRANS PARAMS', $params);

            $transactionResponse = Snap::createTransaction($params);

            $transaction->update([
                'status' => 'pending',
                'payment_method' => 'midtrans',
            ]);

            return redirect()->away($transactionResponse->redirect_url);

        } catch (\Throwable $e) {
            \Log::error('MIDTRANS ERROR', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}