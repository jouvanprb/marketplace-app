<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'game_account',
        'user_id_game',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relasi: OrderItem belongs to Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: OrderItem belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Get subtotal (price * quantity)
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}