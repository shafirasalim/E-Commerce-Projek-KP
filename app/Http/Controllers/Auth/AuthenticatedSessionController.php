<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;  // ← TAMBAHKAN INI
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ===== MERGE SESSION CART KE DATABASE =====
            $this->mergeCart();
            // ===========================================

            // Kalau ada parameter redirect (misal dari cart checkout)
            if ($request->has('redirect')) {
                return redirect($request->input('redirect'));
            }

            // Kalau admin → ke admin dashboard
            if ($user->role && $user->role->nama_role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Kalau customer → ke home
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Merge session cart ke database cart setelah login
     */
    protected function mergeCart()
    {
        $sessionCart = session()->get('cart', []);
        
        // Kalau gak ada cart di session, skip
        if (empty($sessionCart)) {
            return;
        }

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        
        foreach ($sessionCart as $productId => $item) {
            // Ambil data produk dari database
            $product = Product::find($productId);
            
            if (!$product) {
                continue; // Skip kalau produk gak ada
            }
            
            $cartItem = $cart->items()->where('product_id', $productId)->first();
            
            if ($cartItem) {
                // Kalau udah ada di database, tambah quantity
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $item['quantity']
                ]);
            } else {
                // Kalau belum ada, buat baru
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] ?? $product->price  // ← TAMBAHKAN INI!
                ]);
            }
        }
        
        // Hapus session cart setelah merge
        session()->forget('cart');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}