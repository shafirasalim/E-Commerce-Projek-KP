<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


// 1. WEB PROFIL 

Route::get('/', function () {
    return view('profile.index'); // Halaman depan perusahaan
})->name('home');

Route::get('/about', function () {
    return view('profile.about'); // Halaman tentang kami
})->name('about');

// 2. E-COMMERCE KATALOG (PUBLIK - TANPA LOGIN)

Route::get('/shop', [ProductController::class, 'publicIndex'])->name('shop.index');
Route::get('/shop/{product}', [ProductController::class, 'publicShow'])->name('shop.show');

// 3. AREA WAJIB LOGIN (AUTHENTICATED)

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
       
    });

});

require __DIR__.'/auth.php';