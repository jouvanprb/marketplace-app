<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Create order & redirect to payment
     */
    public function store(Request $request, Product $product)
    {
        // Validation
        $rules = [
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ];

        if ($product->type === 'topup') {
            $rules['game_id'] = 'required|string|max:50';
            $rules['zone_id'] = 'nullable|string|max:20';
        }

        $request->validate($rules);

        // Create Order
        $order = Order::create([
            'user_id' => auth()->id(), // null for guests
            'order_code' => 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'total_price' => $product->price,
            'status' => 'pending',
        ]);

        // Create Order Item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'quantity' => 1,
        ]);

        // Prepare custom customer details for Midtrans
        $customCustomerDetails = [
            'name' => auth()->check() ? auth()->user()->name : 'Guest Customer',
            'email' => $request->email,
            'phone' => $request->phone,
            'game_details' => $product->type === 'topup' ? [
                'game_id' => $request->game_id,
                'zone_id' => $request->zone_id,
            ] : null,
        ];

        // Generate Snap Token
        $snapToken = $this->midtransService->createTransaction($order, $customCustomerDetails);

        return view('payment.checkout', compact('order', 'snapToken'));
    }

    /**
     * Payment finish callback
     */
    public function finish(Request $request)
    {
        $order = Order::where('order_code', $request->order_id)->first();
        
        if ($order) {
            $order->update([
                'status' => 'paid',
                'payment_method' => $request->payment_type ?? 'midtrans',
            ]);
        }

        if (auth()->check()) {
            return redirect()->route('dashboard')->with('success', 'Payment successful!');
        }

        return redirect()->route('home')->with('success', 'Payment successful! Your order has been placed.');
    }

    /**
     * Payment unfinish callback
     */
    public function unfinish(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard')->with('warning', 'Payment pending. Please complete your payment.');
        }

        return redirect()->route('home')->with('warning', 'Payment pending. Please complete your payment.');
    }

    /**
     * Payment error callback
     */
    public function error(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard')->with('error', 'Payment failed. Please try again.');
        }

        return redirect()->route('home')->with('error', 'Payment failed. Please try again.');
    }
}