<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->query('range', 'all');

        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $recentOrders = Order::with('user')->latest()->take(2)->get();
        
        // Revenue (sum of total_price for paid orders)
        $revenueQuery = Order::where('status', 'paid');
        if ($range === 'today') {
            $revenueQuery->whereDate('created_at', today());
        } elseif ($range === 'this_week') {
            $revenueQuery->where('created_at', '>=', now()->startOfWeek());
        } elseif ($range === 'this_month') {
            $revenueQuery->where('created_at', '>=', now()->startOfMonth());
        }
        $revenue = $revenueQuery->sum('total_price');

        // Top 5 Products
        $topProductsQuery = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.name', 'categories.name as category_name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
            ->where('orders.status', 'paid');

        if ($range === 'today') {
            $topProductsQuery->whereDate('orders.created_at', today());
        } elseif ($range === 'this_week') {
            $topProductsQuery->where('orders.created_at', '>=', now()->startOfWeek());
        } elseif ($range === 'this_month') {
            $topProductsQuery->where('orders.created_at', '>=', now()->startOfMonth());
        }

        $topProducts = $topProductsQuery->groupBy('products.id', 'products.name', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Top 5 Categories
        $topCategoriesQuery = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
            ->where('orders.status', 'paid');

        if ($range === 'today') {
            $topCategoriesQuery->whereDate('orders.created_at', today());
        } elseif ($range === 'this_week') {
            $topCategoriesQuery->where('orders.created_at', '>=', now()->startOfWeek());
        } elseif ($range === 'this_month') {
            $topCategoriesQuery->where('orders.created_at', '>=', now()->startOfMonth());
        }

        $topCategories = $topCategoriesQuery->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'recentOrders',
            'revenue',
            'topProducts',
            'topCategories'
        ));
    }
}
