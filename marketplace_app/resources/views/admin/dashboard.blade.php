@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card-admin p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-zinc-400">Total Products</h3>
            <div class="w-8 h-8 rounded-md bg-orange-500/10 flex items-center justify-center text-orange-500">
                <i class="fas fa-box-open text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-semibold text-zinc-100">{{ $totalProducts }}</div>
        <p class="text-xs text-zinc-500 mt-2">Active in store</p>
    </div>
    
    <div class="card-admin p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-zinc-400">Total Orders</h3>
            <div class="w-8 h-8 rounded-md bg-blue-500/10 flex items-center justify-center text-blue-500">
                <i class="fas fa-receipt text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-semibold text-zinc-100">{{ $totalOrders }}</div>
        <p class="text-xs text-zinc-500 mt-2">All time</p>
    </div>
    
    <div class="card-admin p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-zinc-400">Customers</h3>
            <div class="w-8 h-8 rounded-md bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <i class="fas fa-users text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-semibold text-zinc-100">{{ $totalCustomers }}</div>
        <p class="text-xs text-zinc-500 mt-2">Registered accounts</p>
    </div>
    
    <div class="card-admin p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium text-zinc-400">Revenue</h3>
            <div class="w-8 h-8 rounded-md bg-purple-500/10 flex items-center justify-center text-purple-500">
                <i class="fas fa-wallet text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-semibold text-zinc-100">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
        <p class="text-xs text-zinc-500 mt-2">Total revenue</p>
    </div>
</div>

<div class="card-admin">
    <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center">
        <h3 class="text-sm font-semibold text-zinc-100">Recent Transactions</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-orange-500 hover:text-orange-400">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left table-admin">
            <thead>
                <tr>
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($recentOrders as $order)
                <tr>
                    <td class="px-6 py-4 font-medium text-zinc-300">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">{{ $order->user->name }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium 
                            @if($order->status == 'paid') bg-emerald-500/10 text-emerald-500 border border-emerald-500/20
                            @elseif($order->status == 'pending') bg-amber-500/10 text-amber-500 border border-amber-500/20
                            @elseif($order->status == 'failed' || $order->status == 'expired') bg-red-500/10 text-red-500 border border-red-500/20
                            @else bg-[#171717] text-[#8f8f9d] border border-[#1f1f22] @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-zinc-400">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-zinc-400 hover:text-white transition-colors">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500 text-sm">No recent transactions.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <div class="card-admin p-6">
        <h3 class="text-sm font-semibold text-zinc-100 mb-6">Top Selling Products</h3>
        <div class="relative h-64 w-full">
            <canvas id="topProductsChart"></canvas>
        </div>
    </div>
    
    <div class="card-admin p-6">
        <h3 class="text-sm font-semibold text-zinc-100 mb-6">Popular Categories</h3>
        <div class="relative h-64 w-full">
            <canvas id="topCategoriesChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prepare data for Top Products
        const productsData = @json($topProducts);
        const productLabels = productsData.map(item => item.name);
        const productData = productsData.map(item => item.total_sold);
        
        // Prepare data for Top Categories
        const categoriesData = @json($topCategories);
        const categoryLabels = categoriesData.map(item => item.name);
        const categoryData = categoriesData.map(item => item.total_sold);

        // Common Chart.js options for dark minimalist theme
        Chart.defaults.color = '#8f8f9d';
        Chart.defaults.font.family = 'Inter, sans-serif';
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#171717',
                    titleColor: '#ececf1',
                    bodyColor: '#a1a1aa',
                    borderColor: '#1f1f22',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#1f1f22' },
                    border: { display: false },
                    ticks: { precision: 0 }
                },
                x: {
                    grid: { display: false },
                    border: { display: false }
                }
            }
        };

        // Render Top Products Chart (Bar)
        new Chart(document.getElementById('topProductsChart'), {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Total Sold',
                    data: productData,
                    backgroundColor: '#ea580c', // Orange accent
                    borderRadius: 4,
                    barThickness: 20
                }]
            },
            options: chartOptions
        });

        // Render Top Categories Chart (Doughnut)
        new Chart(document.getElementById('topCategoriesChart'), {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: [
                        '#ea580c', '#3b82f6', '#10b981', '#8b5cf6', '#eab308'
                    ],
                    borderWidth: 2,
                    borderColor: '#0a0a0a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#8f8f9d', padding: 20, font: { size: 11 } }
                    },
                    tooltip: chartOptions.plugins.tooltip
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
