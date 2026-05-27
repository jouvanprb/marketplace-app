<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GameStore') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0a0a0c;
        }
        
        .auth-bg {
            background: radial-gradient(circle at top right, #1f1105 0%, #0a0a0c 40%, #0a0a0c 100%);
            background-attachment: fixed;
        }
        
        .card-glass {
            background: rgba(20, 20, 22, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 153, 0, 0.15);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 153, 0, 0.1);
        }
        
        .input-gaming {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            color: white !important;
            border-radius: 0.5rem !important;
        }
        
        .input-gaming:focus {
            background: rgba(255, 153, 0, 0.05) !important;
            border-color: #ff9900 !important;
            box-shadow: 0 0 0 3px rgba(255, 153, 0, 0.15) !important;
            outline: none !important;
            ring: 0 !important;
        }
        
        .input-gaming::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
        }
        
        .btn-primary-gaming {
            background: linear-gradient(135deg, #ffb347 0%, #ff7b00 100%) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: none !important;
            color: #000 !important;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.3);
            font-weight: 700 !important;
            letter-spacing: 0.5px;
        }
        
        .btn-primary-gaming:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 25px rgba(255, 123, 0, 0.4) !important;
            background: linear-gradient(135deg, #ffc16b 0%, #ff8c1a 100%) !important;
        }
        
        .btn-primary-gaming:active {
            transform: translateY(0) !important;
        }
        
        .btn-primary-gaming:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-secondary-gaming {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            transition: all 0.3s ease !important;
            color: #ccc !important;
        }
        
        .btn-secondary-gaming:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 153, 0, 0.3) !important;
            color: #fff !important;
            transform: translateY(-1px);
        }
        
        .neon-text {
            text-shadow: 0 0 15px rgba(255, 153, 0, 0.4), 0 0 30px rgba(255, 153, 0, 0.2);
        }
        
        .game-icon {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .game-icon:hover {
            transform: scale(1.15) rotate(-5deg);
            filter: drop-shadow(0 0 15px rgba(255, 153, 0, 0.6));
        }

        /* Loading spinner */
        .loading-spinner {
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            border-top: 2px solid #000;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Override checkbox */
        input[type="checkbox"] {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        input[type="checkbox"]:checked {
            background-color: #ff9900 !important;
            border-color: #ff9900 !important;
        }

        /* Override link color */
        .link-gaming {
            color: #ff9900 !important;
            transition: all 0.3s ease !important;
        }
        
        .link-gaming:hover {
            color: #ffb347 !important;
            text-shadow: 0 0 8px rgba(255, 153, 0, 0.3);
        }

        .hexagon-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='100' viewBox='0 0 60 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 100L0 75V25L30 0l30 25v50L30 100zM15 82.5l15 12.5 15-12.5V32.5L30 20 15 32.5v50z' fill='%23ff9900' fill-opacity='0.02' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body class="auth-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Effects -->
    <div class="hexagon-bg"></div>
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-orange-600 rounded-full opacity-10 blur-[100px]"></div>
        <div class="absolute top-[20%] -left-20 w-72 h-72 bg-red-600 rounded-full opacity-10 blur-[80px]"></div>
        <div class="absolute -bottom-40 right-[20%] w-80 h-80 bg-orange-500 rounded-full opacity-10 blur-[100px]"></div>
    </div>
    
    <div class="w-full max-w-md relative z-10">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <a href="/" class="inline-block group" wire:navigate>
                <div class="text-5xl mb-3">
                    <i class="fas fa-bolt text-orange-500 game-icon"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-white tracking-tight uppercase">
                    Game<span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600 neon-text">Store</span>
                </h2>
                <p class="text-gray-400 text-sm mt-2 font-medium tracking-wide uppercase opacity-80">Premium Top Up & Trading</p>
            </a>
        </div>

        <!-- Card -->
        <div class="card-glass rounded-2xl p-8">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} GameStore. All rights reserved.</p>
            <div class="mt-3 flex justify-center space-x-6">
                <a href="#" class="hover:text-orange-400 transition-colors">Terms</a>
                <a href="#" class="hover:text-orange-400 transition-colors">Privacy</a>
                <a href="#" class="hover:text-orange-400 transition-colors">Support</a>
            </div>
        </div>
    </div>

    <script>
        // Password toggle function
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.parentElement.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                icon.classList.add('text-orange-400');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                icon.classList.remove('text-orange-400');
            }
        }
    </script>
</body>
</html>