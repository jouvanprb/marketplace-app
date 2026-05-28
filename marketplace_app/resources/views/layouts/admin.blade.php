<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GameStore') }} - Admin Console</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        :root {
            --bg-base: #000000;
            --bg-surface: #0a0a0a;
            --bg-surface-hover: #171717;
            --border-color: #1f1f22;
            --accent: #ea580c; /* Refined orange */
            --accent-hover: #c2410c;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-base);
            color: #ececf1;
            -webkit-font-smoothing: antialiased;
        }
        
        .sidebar {
            background-color: var(--bg-base);
            border-right: 1px solid var(--border-color);
        }

        .nav-link {
            transition: all 0.2s ease;
            color: #8f8f9d;
            border-radius: 0.375rem;
            margin: 0.125rem 0.75rem;
            font-size: 0.875rem;
        }

        .nav-link:hover {
            background-color: var(--bg-surface-hover);
            color: #ececf1;
        }

        .nav-link.active {
            background-color: transparent;
            color: #ececf1;
            font-weight: 500;
        }
        
        .nav-link.active i {
            color: var(--accent);
        }

        .card-admin {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
        }

        .btn-primary-admin {
            background-color: #ececf1;
            color: #000;
            font-weight: 500;
            font-size: 0.875rem;
            transition: background-color 0.2s ease;
            border-radius: 0.375rem;
        }

        .btn-primary-admin:hover {
            background-color: #d1d5db;
        }

        .table-admin th {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            color: #8f8f9d;
            font-weight: 400;
            text-transform: capitalize;
            font-size: 0.75rem;
        }

        .table-admin td {
            border-bottom: 1px solid var(--border-color);
            color: #ececf1;
        }
        
        .table-admin tr:last-child td {
            border-bottom: none;
        }
        
        .table-admin tr:hover td {
            background-color: var(--bg-surface-hover);
        }

        /* Form Inputs Minimalist */
        input[type="text"], input[type="number"], input[type="email"], textarea, select {
            background-color: #0a0a0a !important;
            border: 1px solid var(--border-color) !important;
            color: #ececf1 !important;
            box-shadow: none !important;
        }
        input:focus, textarea:focus, select:focus {
            border-color: #52525b !important;
            ring: 0 !important;
        }

        /* Minimalist scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #27272a;
            border-radius: 2px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3f3f46;
        }
    </style>
</head>
<body class="min-h-screen bg-[#09090b]" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" style="display: none;" class="fixed inset-0 z-10 bg-black/60 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

    <!-- Sidebar -->
    <aside class="sidebar w-64 fixed inset-y-0 left-0 z-20 flex flex-col h-screen transform transition-transform duration-300 -translate-x-full lg:translate-x-0"
           :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        <div class="h-16 flex items-center justify-between px-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 text-white font-semibold text-lg tracking-tight">
                <i class="fas fa-gamepad text-orange-500"></i>
                <span>Store<span class="text-orange-500">Console</span></span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-[#8f8f9d] hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4">
            <div class="px-5 py-2 text-[10px] font-semibold text-[#8f8f9d] uppercase tracking-widest mb-1">
                Overview
            </div>
            
            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-4 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line w-6 text-center"></i>
                <span class="ml-2">Analytics</span>
            </a>
            
            <div class="px-5 py-2 text-[10px] font-semibold text-[#8f8f9d] uppercase tracking-widest mt-6 mb-1">
                Store Management
            </div>
            
            <a href="{{ route('admin.categories.index') }}" class="nav-link flex items-center px-4 py-2.5 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-layer-group w-6 text-center"></i>
                <span class="ml-2">Categories</span>
            </a>
            
            <div x-data="{ openProducts: {{ request()->routeIs('admin.products.*') ? 'true' : 'false' }} }">
                <button @click="openProducts = !openProducts" class="w-[calc(100%-1.5rem)] flex items-center justify-between px-4 py-2.5 mx-3 my-0.5 text-sm text-[#8f8f9d] hover:bg-[#171717] hover:text-[#ececf1] rounded-md transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-box-open w-6 text-center {{ request()->routeIs('admin.products.*') ? 'text-[#ea580c]' : '' }}"></i>
                        <span class="ml-2 {{ request()->routeIs('admin.products.*') ? 'text-[#ececf1] font-medium' : '' }}">Products</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': openProducts}"></i>
                </button>
                
                <div x-show="openProducts" style="display: none;" class="pl-11 pr-3 py-1 space-y-1">
                    <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 text-xs rounded-md transition-colors {{ request()->routeIs('admin.products.index') && !request()->has('type') ? 'text-white font-medium bg-[#171717]' : 'text-[#8f8f9d] hover:text-white hover:bg-[#171717]' }}">
                        All Products
                    </a>
                    <a href="{{ route('admin.products.index', ['type' => 'topup']) }}" class="block px-3 py-2 text-xs rounded-md transition-colors {{ request()->query('type') === 'topup' ? 'text-white font-medium bg-[#171717]' : 'text-[#8f8f9d] hover:text-white hover:bg-[#171717]' }}">
                        Top Up
                    </a>
                    <a href="{{ route('admin.products.index', ['type' => 'account']) }}" class="block px-3 py-2 text-xs rounded-md transition-colors {{ request()->query('type') === 'account' ? 'text-white font-medium bg-[#171717]' : 'text-[#8f8f9d] hover:text-white hover:bg-[#171717]' }}">
                        Accounts
                    </a>
                </div>
            </div>
            
            <a href="{{ route('admin.orders.index') }}" class="nav-link flex items-center px-4 py-2.5 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-receipt w-6 text-center"></i>
                <span class="ml-2">Orders</span>
            </a>
        </nav>
        
        <div class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="confirmAction(event, 'Sign Out', 'Are you sure you want to log out of the admin console?', 'Yes, sign out', false)" class="flex items-center w-full px-4 py-2 text-sm text-[#8f8f9d] hover:text-white hover:bg-[#171717] rounded-md transition-colors">
                    <i class="fas fa-sign-out-alt w-6 text-center"></i>
                    <span class="ml-2">Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex flex-col min-h-screen transition-all duration-300 lg:ml-64">
        <!-- Topbar -->
        <header class="h-16 flex items-center justify-between px-4 sm:px-8 bg-[#000000] sticky top-0 z-10 w-full">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="text-[#8f8f9d] hover:text-white mr-4 lg:hidden transition-colors">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <h1 class="text-lg font-medium text-white tracking-tight truncate">@yield('header')</h1>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-[#ececf1]">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="w-8 h-8 sm:w-8 sm:h-8 rounded-full bg-[#171717] flex items-center justify-center text-[#ececf1] text-xs font-medium border border-[#1f1f22]">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-8 overflow-x-hidden w-full max-w-[100vw]">
            @yield('content')
        </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toast Notification for Session Messages
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#18181b', // Lighter zinc-900 background
            color: '#ececf1',
            iconColor: '#ea580c',
            customClass: {
                popup: 'border border-[#27272a] rounded-lg shadow-xl shadow-black/60'
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session("success") }}',
                iconColor: '#10b981'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session("error") }}',
                iconColor: '#ef4444'
            });
        @endif

        // Confirmation Modal for Delete/Logout
        function confirmAction(event, title, message, buttonText, isDanger = true) {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isDanger ? '#ef4444' : '#ea580c',
                cancelButtonColor: '#27272a',
                background: '#18181b', // Lighter zinc-900 background
                color: '#ececf1',
                confirmButtonText: buttonText,
                customClass: {
                    popup: 'border border-[#27272a] rounded-xl shadow-2xl shadow-black/80',
                    cancelButton: 'text-[#ececf1] hover:bg-[#3f3f46] transition-colors border border-[#3f3f46]'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
