<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Buy Now - Langsung checkout 1 produk
     */
    public function buyNow($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu');
        }

        $product = Product::findOrFail($productId);
        
        if ((int)$product->stock < 1) {
            return back()->with('error', 'Stok produk tidak mencukupi');
        }

        // Simpan ke session untuk buy now
        session()->put('buy_now', [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image' => $product->image,
        ]);

        return redirect()->route('checkout.index');
    }

    /**
     * Tampilkan halaman checkout
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk melanjutkan checkout');
        }

        // Merge session cart ke database
        $this->mergeSessionCart();

        // Cek apakah ini "Buy Now"
        $buyNowItem = session()->get('buy_now');
        
        if ($buyNowItem) {
            // Buy Now - hanya 1 item
            $checkoutItems = collect([$buyNowItem]);
            $total = $buyNowItem['price'] * $buyNowItem['quantity'];
        } else {
            // Checkout dari cart - ambil item yang dipilih
            $selectedIds = $request->input('selected_items', []);
            
            if (empty($selectedIds)) {
                return redirect()->route('cart.index')->with('error', 'Pilih minimal 1 produk untuk checkout');
            }

            $cart = Cart::with(['items.product'])->where('user_id', Auth::id())->first();
            
            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong');
            }

            // Filter hanya item yang dipilih
            $checkoutItems = $cart->items->whereIn('product_id', $selectedIds);
            
            if ($checkoutItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Pilih minimal 1 produk untuk checkout');
            }

            // Hitung total
            $total = $checkoutItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
        }

        return view('checkout.index', compact('checkoutItems', 'total', 'buyNowItem'));
    }

    /**
     * Helper: Ambil data item (support array & object)
     */
    private function getItemData($item)
    {
        if (is_array($item)) {
            return [
                'product_id' => $item['product_id'] ?? null,
                'quantity' => (int)($item['quantity'] ?? 1),
                'price' => $item['price'] ?? 0,
                'name' => $item['name'] ?? 'Produk',
                'product' => isset($item['product_id']) ? Product::find($item['product_id']) : null,
            ];
        } else {
            // CartItem model
            return [
                'product_id' => $item->product_id,
                'quantity' => (int)$item->quantity,
                'price' => $item->price,
                'name' => $item->product->name ?? 'Produk',
                'product' => $item->product ?? Product::find($item->product_id),
            ];
        }
    }

    /**
     * Proses checkout
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string',
            'notes' => 'nullable|string|max:500',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:products,id',
        ]);

        // Cek buy now atau cart
        $buyNowItem = session()->get('buy_now');
        
        if ($buyNowItem) {
            $itemsToProcess = collect([$buyNowItem]);
        } else {
            $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
            $itemsToProcess = $cart->items->whereIn('product_id', $validated['selected_items']);
        }

        // ===== VALIDASI STOK (FIXED!) =====
        foreach ($itemsToProcess as $item) {
            $data = $this->getItemData($item);
            $product = $data['product'];
            $qty = $data['quantity'];
            
            if (!$product) {
                return back()->with('error', 'Produk tidak ditemukan.');
            }
            
            $stock = (int)$product->stock;
            
            if ($stock < $qty) {
                return back()->with('error', "Stok produk \"{$data['name']}\" tidak mencukupi. Tersedia: {$stock}, Diminta: {$qty}");
            }
        }
        // ===================================

        $total = $itemsToProcess->sum(function($item) {
            $data = $this->getItemData($item);
            return $data['price'] * $data['quantity'];
        });

        DB::beginTransaction();

        try {
            // Buat Transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
                'transaction_date' => now(),
            ]);

            // Buat Transaction Details dan reduce stock
            foreach ($itemsToProcess as $item) {
                $data = $this->getItemData($item);
                
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],
                    'subtotal' => $data['price'] * $data['quantity'],
                ]);

                // Kurangi stok (pakai lock biar aman dari race condition)
                $product = Product::lockForUpdate()->find($data['product_id']);
                $product->decrement('stock', $data['quantity']);
            }

            // JANGAN HAPUS CART DI SINI!
            // Cart akan dihapus di PaymentController::notification() setelah pembayaran sukses

            // Hapus session buy now
            session()->forget('buy_now');

            DB::commit();

            // LANGSUNG REDIRECT KE MIDTRANS!
            return redirect()->route('payment.redirect', $transaction->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error buat debug
            \Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Merge session cart ke database cart
     */
    private function mergeSessionCart()
    {
        $sessionCart = session()->get('cart', []);
        
        if (empty($sessionCart)) {
            return;
        }

        $cart = Cart::firstOrCreate(['user_id', Auth::id()]);

        foreach ($sessionCart as $productId => $item) {
            $product = Product::find($productId);
            
            if (!$product) {
                continue; // Skip produk yang udah gak ada
            }
            
            $cartItem = $cart->items()->where('product_id', $productId)->first();

            if ($cartItem) {
                $cartItem->update(['quantity' => $cartItem->quantity + $item['quantity']]);
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] ?? $product->price,
                ]);
            }
        }

        session()->forget('cart');
    }
}