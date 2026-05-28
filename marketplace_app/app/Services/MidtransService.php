<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Snap Token for payment
     */
    public function createTransaction(Order $order, array $customCustomerDetails = [])
    {
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product->name,
            ];
        }

        $customerName = $customCustomerDetails['name'] ?? ($order->user->name ?? 'Customer');
        $customerEmail = $customCustomerDetails['email'] ?? ($order->user->email ?? 'guest@example.com');
        $customerPhone = $customCustomerDetails['phone'] ?? ($order->user->phone ?? '08123456789');

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) $order->total_price,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'duration' => 15,
                'unit' => 'minute',
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error' => route('payment.error'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $order->update([
                'payment_token' => $snapToken,
                'payment_details' => array_merge($params, [
                    'game_details' => $customCustomerDetails['game_details'] ?? null
                ]),
            ]);

            return $snapToken;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Snap JS URL
     */
    public function getSnapUrl()
    {
        return Config::$isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }
}
