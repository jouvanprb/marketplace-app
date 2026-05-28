<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Marketplace'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #09090b;
            color: #ececf1;
        }
        
        .glass-nav {
            background: rgba(9, 9, 11, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .card-glass:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .btn-glow {
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            box-shadow: 0 0 15px rgba(234, 88, 12, 0.4);
        }

        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #27272a;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3f3f46;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">
    
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Desktop Nav -->
                <div class="flex items-center flex-1">
                    <a href="{{ route('home') }}" class="flex items-center mr-8">
                        <img src="{{ asset('images/johen.png') }}" alt="Logo" class="h-8 w-auto object-contain">
                    </a>
                    
                    <!-- Search Input in Top Bar -->
                    <form action="{{ route('home') }}" method="GET" class="relative max-w-xs w-full hidden md:block">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search game..." 
                               class="w-full bg-[#111113] border border-zinc-800/80 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-orange-500 transition-all placeholder-zinc-500 shadow-inner">
                    </form>
                </div>

                <!-- Right Side Nav -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    @auth
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-zinc-300 hover:text-white transition-colors focus:outline-none">
                                <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-[10px] ml-1 opacity-70"></i>
                            </button>
                            <div x-show="open" x-transition.opacity style="display: none;" class="absolute right-0 mt-2 w-48 bg-[#111113] border border-zinc-800 rounded-lg shadow-xl py-1 z-50 overflow-hidden">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block w-full text-left px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                        Admin Panel
                                    </a>
                                @else
                                    <a href="{{ route('profile') }}" class="block w-full text-left px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                        My Account
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-zinc-800 hover:text-red-300 transition-colors">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-zinc-400 hover:text-zinc-100 px-4 py-2 text-sm font-semibold transition-all duration-200 hover:bg-zinc-900/60 rounded-xl">Log In</a>
                        <a href="{{ route('register') }}" class="bg-zinc-100 hover:bg-zinc-200 text-zinc-950 px-4 py-2 text-sm font-semibold transition-all duration-200 rounded-xl shadow-md hover:-translate-y-0.5 transform active:scale-95">Sign Up</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-zinc-400 hover:text-white focus:outline-none">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" style="display: none;"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition style="display: none;" class="md:hidden bg-[#09090b] border-b border-zinc-800">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-zinc-900">Home</a>
            </div>
            <div class="pt-4 pb-3 border-t border-zinc-800">
                @auth
                    <div class="px-4 flex items-center mb-3">
                        <div>
                            <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                            <div class="text-sm font-medium text-zinc-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="px-2 space-y-1">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-zinc-300 hover:text-white hover:bg-zinc-800">Admin Panel</a>
                        @else
                            <a href="{{ route('profile') }}" class="block px-3 py-2 rounded-md text-base font-medium text-zinc-300 hover:text-white hover:bg-zinc-800">My Account</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-400 hover:text-red-300 hover:bg-zinc-800">Sign Out</button>
                        </form>
                    </div>
                @else
                    <div class="px-4 flex flex-col gap-2">
                        <a href="{{ route('login') }}" class="block text-center w-full px-4 py-2.5 text-sm font-semibold text-zinc-300 bg-zinc-900 border border-zinc-800/80 rounded-xl hover:bg-zinc-800 hover:text-white transition-all duration-200">Log In</a>
                        <a href="{{ route('register') }}" class="block text-center w-full px-4 py-2.5 text-sm font-semibold text-zinc-950 bg-zinc-100 hover:bg-zinc-200 rounded-xl shadow-md transition-all duration-200">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#050505] border-t border-zinc-800/50 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-1">
                    <a href="{{ route('home') }}" class="inline-block mb-4">
                        <img src="{{ asset('images/johen.png') }}" alt="Logo" class="h-8 w-auto object-contain">
                    </a>
                    <p class="text-zinc-500 text-sm mb-4">The ultimate destination for premium game accounts and fast, reliable top-ups.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-zinc-500 hover:text-white transition-colors"><i class="fab fa-discord"></i></a>
                        <a href="#" class="text-zinc-500 hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-zinc-500 hover:text-white transition-colors"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Services</h3>
                    <ul class="space-y-2 text-sm text-zinc-400">
                        <li><a href="{{ route('home') }}?type=topup" class="hover:text-orange-500 transition-colors">Game Top Up</a></li>
                        <li><a href="{{ route('home') }}?type=account" class="hover:text-orange-500 transition-colors">Buy Account</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Gift Cards</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-sm text-zinc-400">
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Payment Methods</h3>
                    <div class="flex flex-wrap gap-2">
                        <div class="bg-zinc-900 px-3 py-1.5 rounded text-xs text-zinc-400 font-medium border border-zinc-800">GoPay</div>
                        <div class="bg-zinc-900 px-3 py-1.5 rounded text-xs text-zinc-400 font-medium border border-zinc-800">OVO</div>
                        <div class="bg-zinc-900 px-3 py-1.5 rounded text-xs text-zinc-400 font-medium border border-zinc-800">DANA</div>
                        <div class="bg-zinc-900 px-3 py-1.5 rounded text-xs text-zinc-400 font-medium border border-zinc-800">QRIS</div>
                        <div class="bg-zinc-900 px-3 py-1.5 rounded text-xs text-zinc-400 font-medium border border-zinc-800">Virtual Account</div>
                    </div>
                </div>
            </div>
            <div class="border-t border-zinc-800 mt-12 pt-8 text-center text-sm text-zinc-600">
                &copy; {{ date('Y') }} Marketplace Game. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
