<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Home page: Show catalog of games (Categories)
     */
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->withCount(['products' => function($q) {
            $q->active();
        }])->get();

        return view('front.index', compact('categories'));
    }

    /**
     * Dedicated Game Page: Midasbuy-style purchasing hub for a specific game
     */
    public function game($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $topups = Product::where('category_id', $category->id)
            ->active()
            ->where('type', 'topup')
            ->orderBy('price', 'asc')
            ->get();
            
        $accounts = Product::where('category_id', $category->id)
            ->active()
            ->where('type', 'account')
            ->latest()
            ->get();

        return view('front.game', compact('category', 'topups', 'accounts'));
    }

    /**
     * Optional: Keep detail page for account type products
     */
    public function show($id)
    {
        $product = Product::with('category')->active()->findOrFail($id);
        
        // Find related products in the same category
        $relatedProducts = Product::with('category')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('front.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the track order form or logged-in user's transaction history
     */
    public function trackIndex()
    {
        if (auth()->check()) {
            $orders = \App\Models\Order::with('items.product')
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return view('front.track', compact('orders'));
        }

        return view('front.track');
    }

    /**
     * Handle the tracking form submission for guests
f     */
    public function trackSearch(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $phone = trim($request->phone);
        $orders = \App\Models\Order::with('items.product')
            ->where('payment_details->customer_details->phone', $phone)
            ->latest()
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'No transaction history found for this phone number. Please check your number.')->withInput();
        }

        return view('front.track', compact('orders', 'phone'));
    }
}
