<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Http\Controllers\FrontController;
use App\Http\Controllers\CheckoutController;

// PUBLIC ROUTES 
Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/game/{slug}', [FrontController::class, 'game'])->name('game.show');
Route::get('/product/{id}', [FrontController::class, 'show'])->name('product.show');

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
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/dashboard', function () {
        return redirect()->route('profile');
    });
    
    Route::get('/orders', function () {
        return view('customer.orders');
    })->name('customer.orders');

    Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    })->name('profile.update');
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

// PAYMENT & CHECKOUT ROUTES (PUBLIC)
Route::post('/checkout/{product}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/finish', [CheckoutController::class, 'finish'])->name('payment.finish');
Route::get('/payment/unfinish', [CheckoutController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [CheckoutController::class, 'error'])->name('payment.error');