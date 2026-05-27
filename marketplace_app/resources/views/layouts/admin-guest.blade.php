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
        
        .card-admin-login {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
        }

        .input-minimal {
            background-color: #0a0a0a !important;
            border: 1px solid var(--border-color) !important;
            color: #ececf1 !important;
            box-shadow: none !important;
            border-radius: 0.375rem;
        }
        .input-minimal:focus {
            border-color: #52525b !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .btn-minimal {
            background-color: #ececf1;
            color: #000;
            font-weight: 500;
            font-size: 0.875rem;
            transition: background-color 0.2s ease;
            border-radius: 0.375rem;
        }
        .btn-minimal:hover {
            background-color: #d1d5db;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-[360px]">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center space-x-2 text-white font-semibold text-xl tracking-tight mb-2">
                <i class="fas fa-gamepad text-[#ea580c]"></i>
                <span>Store<span class="text-[#ea580c]">Console</span></span>
            </a>
            <p class="text-[10px] text-[#8f8f9d] uppercase tracking-widest mt-1">Admin Authentication</p>
        </div>

        <div class="card-admin-login p-8">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
