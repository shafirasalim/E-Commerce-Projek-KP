<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Cart;       // <-- TAMBAHAN: Import Model Cart
use App\Models\CartItem;   // <-- TAMBAHAN: Import Model CartItem
use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => strtolower(trim($request->email)),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $customerRole = Role::where('nama_role', 'customer')->first();

        $user = User::create([
            'role_id' => $customerRole ? $customerRole->id : null, // Auto-assign role customer
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Mail::to($user->email)->send(new WelcomeMail($user));

        event(new Registered($user));

        Auth::login($user);

        $this->mergeCartToDatabase($user);

        return redirect()->route('dashboard');
    }

    private function mergeCartToDatabase($user)
    {
        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart)) {
            return;
        }

        $userCart = Cart::firstOrCreate([
            'user_id' => $user->id
        ]);

        foreach ($sessionCart as $productId => $item) {
            $existingCartItem = CartItem::where('cart_id', $userCart->id)
                                        ->where('product_id', $productId)
                                        ->first();

            if ($existingCartItem) {
                $existingCartItem->quantity += $item['quantity'];
                $existingCartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] ?? 0, 
                ]);
            }
        }
        session()->forget('cart');
    }
}