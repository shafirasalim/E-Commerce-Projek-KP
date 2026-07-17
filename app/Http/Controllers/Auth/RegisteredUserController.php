<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
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
        // Auto lowercase email sebelum validasi
        $request->merge([
            'email' => strtolower(trim($request->email)),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Cari role 'customer' dari database
        $customerRole = Role::where('nama_role', 'customer')->first();

        $user = User::create([
            'role_id' => $customerRole ? $customerRole->id : null, // Auto-assign role customer
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // === KIRIM EMAIL SELAMAT DATANG ===
        Mail::to($user->email)->send(new WelcomeMail($user));
        // ===================================

        event(new Registered($user));

        Auth::login($user);

        // Ganti RouteServiceProvider::HOME dengan route('dashboard')
        return redirect()->route('dashboard');
    }
}