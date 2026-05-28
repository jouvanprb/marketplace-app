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
            
            return redirect()->route('order.receipt', ['order_code' => $order->order_code])->with('success', 'Payment successful!');
        }

        return redirect()->route('home')->with('success', 'Payment processed successfully!');
    }

    /**
     * Payment unfinish callback
     */
    public function unfinish(Request $request)
    {
        $order = Order::where('order_code', $request->order_id)->first();
        
        if ($order) {
            return redirect()->route('order.receipt', ['order_code' => $order->order_code])->with('warning', 'Payment pending.');
        }

        return redirect()->route('home')->with('warning', 'Payment pending.');
    }

    /**
     * Payment error callback
     */
    public function error(Request $request)
    {
        $order = Order::where('order_code', $request->order_id)->first();
        
        if ($order) {
            $order->update(['status' => 'failed']);
            return redirect()->route('order.receipt', ['order_code' => $order->order_code])->with('error', 'Payment failed.');
        }

        return redirect()->route('home')->with('error', 'Payment failed.');
    }

    /**
     * Handle Midtrans asynchronous notification (Webhook)
     */
    public function notification(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        
        // Formulate double-checking signature verification key
        $localSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($signatureKey !== $localSignature) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }
        
        $order = Order::where('order_code', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        $transactionStatus = $request->input('transaction_status');
        $paymentType = $request->input('payment_type');
        
        if ($transactionStatus == 'capture') {
            if ($paymentType == 'credit_card') {
                if ($request->input('fraud_status') == 'challenge') {
                    $order->update(['status' => 'pending']);
                } else {
                    $order->update(['status' => 'paid', 'payment_method' => $paymentType]);
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $order->update(['status' => 'paid', 'payment_method' => $paymentType]);
        } elseif ($transactionStatus == 'pending') {
            $order->update(['status' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update(['status' => 'failed']);
        }
        
        return response()->json(['message' => 'Webhook notification processed successfully']);
    }

    /**
     * Display order receipt
     */
    public function receipt($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        
        return view('payment.receipt', compact('order'));
    }
}