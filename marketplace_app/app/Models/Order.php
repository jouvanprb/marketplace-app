<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_code',
        'total_price',
        'status',
        'payment_method',
        'payment_details',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'payment_details' => 'array',
    ];

    // Auto generate order code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_code)) {
                $order->order_code = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }

    // Relasi: Order belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Order has many OrderItems
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope: Filter by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Filter by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}