<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketplaceController::class, 'index'])->name('home');
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/artists', [MarketplaceController::class, 'artists'])->name('artists.index');
Route::get('/artwork/{artwork:slug}', [MarketplaceController::class, 'show'])->name('artworks.show');
Route::get('/artists/{artist:username}', [MarketplaceController::class, 'artist'])->name('artists.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{artwork}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{artwork}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/download/{orderItem}', [OrderController::class, 'download'])->name('orders.download');

    Route::get('/sell', [SellerController::class, 'index'])->name('seller.dashboard');
    Route::post('/sell/activate', [SellerController::class, 'activate'])->name('seller.activate');
    Route::get('/sell/artworks/create', [ArtworkController::class, 'create'])->name('seller.artworks.create');
    Route::post('/sell/artworks', [ArtworkController::class, 'store'])->name('seller.artworks.store');
    Route::get('/sell/artworks/{artwork}/edit', [ArtworkController::class, 'edit'])->name('seller.artworks.edit');
    Route::put('/sell/artworks/{artwork}', [ArtworkController::class, 'update'])->name('seller.artworks.update');
    Route::delete('/sell/artworks/{artwork}', [ArtworkController::class, 'destroy'])->name('seller.artworks.destroy');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::patch('/artworks/{artwork}', [AdminController::class, 'reviewArtwork'])->name('artworks.review');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
    });
});
