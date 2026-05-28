<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GameStore') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --bg-base: #000000;
            --bg-surface: #0a0a0a;
            --border-color: #1f1f22;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-base);
            color: #ececf1;
            -webkit-font-smoothing: antialiased;
        }
        
        .card-glass {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
        }
        
        .input-gaming {
            background-color: #0a0a0a !important;
            border: 1px solid var(--border-color) !important;
            color: #ececf1 !important;
            box-shadow: none !important;
            border-radius: 0.375rem !important;
            font-size: 0.875rem !important;
        }
        
        .input-gaming:focus {
            border-color: #52525b !important;
            outline: none !important;
            box-shadow: none !important;
            background-color: #0a0a0a !important;
        }
        
        .input-gaming::placeholder {
            color: #52525b !important;
        }
        
        .btn-primary-gaming {
            background-color: #ececf1 !important;
            color: #000000 !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            transition: background-color 0.2s ease !important;
            border-radius: 0.375rem !important;
            text-shadow: none !important;
            border: none !important;
        }
        
        .btn-primary-gaming:hover {
            background-color: #d1d5db !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-primary-gaming:active {
            transform: none !important;
        }
        
        .btn-primary-gaming:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-secondary-gaming {
            background-color: #0a0a0a !important;
            border: 1px solid var(--border-color) !important;
            color: #ececf1 !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease !important;
            border-radius: 0.375rem !important;
        }
        
        .btn-secondary-gaming:hover {
            background-color: #171717 !important;
            border-color: #52525b !important;
            color: #ececf1 !important;
            transform: none !important;
        }
        
        /* Loading spinner */
        .loading-spinner {
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            border-top: 2px solid #000000;
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
            background-color: #0a0a0a !important;
            border: 1px solid var(--border-color) !important;
        }
        
        input[type="checkbox"]:checked {
            background-color: #ea580c !important;
            border-color: #ea580c !important;
        }

        /* Override link color */
        .link-gaming {
            color: #8f8f9d !important;
            transition: color 0.2s ease !important;
        }
        
        .link-gaming:hover {
            color: #ececf1 !important;
            text-shadow: none !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-[#000000]">
    
    <div class="w-full max-w-[360px] relative z-10">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center space-x-2 text-white font-semibold text-xl tracking-tight mb-2" wire:navigate>
                <img src="{{ asset('images/johen.png') }}" alt="Logo" class="h-10 w-auto object-contain">
            </a>
            <p class="text-[10px] text-[#8f8f9d] uppercase tracking-widest mt-2">Login Authentication</p>
        </div>

        <!-- Card -->
        <div class="card-glass p-8">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-[#8f8f9d] text-[10px] uppercase tracking-wider">
            <p>&copy; {{ date('Y') }} GameStore. All rights reserved.</p>
            <div class="mt-3 flex justify-center space-x-6">
                <a href="#" class="hover:text-white transition-colors">Terms</a>
                <a href="#" class="hover:text-white transition-colors">Privacy</a>
                <a href="#" class="hover:text-white transition-colors">Support</a>
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
                icon.classList.add('text-white');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                icon.classList.remove('text-white');
            }
        }
    </script>
</body>
</html>