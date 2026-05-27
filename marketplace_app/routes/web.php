<?php

use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES 
Route::get('/', function () {
    return view('welcome');
})->name('home');

// AUTH ROUTES (BREEZE) 
require __DIR__.'/auth.php';

// CUSTOMER ROUTES 
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/orders', function () {
        return view('customer.orders');
    })->name('customer.orders');
});

// ADMIN ROUTES 
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('categories.index');
    
    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('products.index');
    
    Route::get('/orders', function () {
        return view('admin.orders.index');
    })->name('orders.index');
});