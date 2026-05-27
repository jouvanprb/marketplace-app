<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// PUBLIC ROUTES 
Route::get('/', function () {
    return view('welcome');
})->name('home');

// AUTH ROUTES (BREEZE) 
require __DIR__.'/auth.php';

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

// CUSTOMER ROUTES 
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/orders', function () {
        return view('customer.orders');
    })->name('customer.orders');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile'); 
});

// ADMIN AUTH ROUTES
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Volt::route('/login', 'pages.auth.admin-login')->name('login');
});

// ADMIN ROUTES 
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    
    // Orders
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
});