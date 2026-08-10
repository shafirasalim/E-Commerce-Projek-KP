<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =================================================================
// 0. WEBHOOK MIDTRANS (WAJIB DI LUAR MIDDLEWARE 'auth'!)
// =================================================================
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])->name('midtrans.notification');


// =================================================================
// 1. WEB PROFIL (PUBLIK)
// =================================================================
Route::get('/', function () {
    $categories = \App\Models\Category::latest()->get();
    $products = \App\Models\Product::where('status', 'active')->latest()->take(12)->get();
    return view('profile.index', compact('categories', 'products'));
})->name('home');

Route::get('/about', function () {
    return view('profile.about');
})->name('about');


// =================================================================
// 2. E-COMMERCE KATALOG & CART (PUBLIK)
// =================================================================
Route::get('/shop', [ProductController::class, 'publicIndex'])->name('shop.index');
Route::get('/shop/{product}', [ProductController::class, 'publicShow'])->name('shop.show');

// Cart (Guest & User)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');


// =================================================================
// 3. AREA WAJIB LOGIN (USER & ADMIN)
// =================================================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard redirect BERDASARKAN ROLE (SUDAH DIPERBAIKI!)
    Route::get('/dashboard', function () {
        if (Auth::check() && Auth::user()->role && Auth::user()->role->nama_role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    // Supplier Application
    Route::get('/apply-supplier', [SupplierApplicationController::class, 'create'])->name('supplier.apply');
    Route::post('/apply-supplier', [SupplierApplicationController::class, 'store'])->name('supplier.store');

    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/buy-now/{productId}', [CheckoutController::class, 'buyNow'])->name('checkout.buy_now');

    // Payment Routes
    Route::get('/payment/{transactionId}', [PaymentController::class, 'redirectToMidtrans'])->name('payment.redirect');
    Route::get('/payment/{transactionId}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{transactionId}/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');

    // Pesanan Saya (User)
    Route::get('/my-orders', function (Request $request) {
        $query = \App\Models\Transaction::where('user_id', Auth::id())->with('details.product');
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    })->name('orders.index');
    
    Route::get('/my-orders/{id}', function ($id) {
        $order = \App\Models\Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('details.product')
            ->firstOrFail();
        return view('orders.show', compact('order'));
    })->name('orders.show');


    // =================================================================
    // 4. ADMIN ROUTES (DIGABUNG & DIRAPIKAN)
    // =================================================================
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Admin Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Admin Orders
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Admin Products
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        
        // Admin Categories
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
       
        // Admin Supplier Management
        Route::get('/suppliers', [\App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/{id}', [\App\Http\Controllers\Admin\SupplierController::class, 'show'])->name('suppliers.show');
        Route::post('/suppliers/{id}/approve', [\App\Http\Controllers\Admin\SupplierController::class, 'approve'])->name('suppliers.approve');
        Route::post('/suppliers/{id}/reject', [\App\Http\Controllers\Admin\SupplierController::class, 'reject'])->name('suppliers.reject');
    
    }); // End Admin Group

}); // End Auth Group


// Auth Routes (Breeze/Jetstream default)
require __DIR__.'/auth.php';