<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'type',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi: Product belongs to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Product has many OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope: Product aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: Filter by category
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}