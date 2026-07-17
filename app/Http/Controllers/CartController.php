<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tampilkan halaman keranjang
    public function index()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        } else {
            $sessionCart = session()->get('cart', []);
            $cartItems = collect($sessionCart)->map(function($item, $productId) {
                $product = Product::find($productId);
                
                return [
                    'id' => $productId,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'image' => $item['image'] ?? null,
                    'name' => $item['name'],
                    'product' => $product
                ];
            });
        }

        return view('cart.index', compact('cartItems'));
    }

    // Tambah produk ke keranjang
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        
        $product = Product::findOrFail($productId);

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartItem = $cart->items()->where('product_id', $productId)->first();
            
            if ($cartItem) {
                $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price
                ]);
            }
        } else {
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image
                ];
            }
            
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // Update quantity item di keranjang
   public function update(Request $request, $itemId)
    {
        try {
            $quantity = $request->input('quantity');
            $productId = $itemId;

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $item = $cart->items()->where('product_id', $productId)->first();
                    if ($item) {
                        if ($quantity > 0) {
                            $item->update(['quantity' => $quantity]);
                        } else {
                            $item->delete();
                        }
                    }
                }
            } else {
                $cart = session()->get('cart', []);
                if (isset($cart[$productId])) {
                    if ($quantity > 0) {
                        $cart[$productId]['quantity'] = $quantity;
                    } else {
                        unset($cart[$productId]);
                    }
                    session()->put('cart', $cart);
                }
            }

            // Return JSON biar JavaScript seneng
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diupdate!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat update keranjang'
            ], 500);
        }
    }

    // Hapus item dari keranjang
    public function remove($productId)
    {
        try {
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $cart->items()->where('product_id', $productId)->delete();
                }
            } else {
                $cart = session()->get('cart', []);
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }

            // Return JSON response yang benar
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus produk'
            ], 500);
        }
    }

    // Clear keranjang
    public function clear()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cart->items()->delete();
            }
        } else {
            session()->forget('cart');
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
    }
}