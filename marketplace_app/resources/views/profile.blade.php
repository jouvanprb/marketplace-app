@extends('layouts.front')

@section('title', 'My Profile - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-24">
    
    <!-- Breadcrumbs -->
    <nav class="flex text-sm text-zinc-500 mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="text-zinc-500 mx-2">/</span>
                    <span class="text-zinc-300">My Account</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <!-- Success Status Message -->
    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-semibold rounded-2xl flex items-center">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2.5 animate-pulse"></span>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        
        <!-- 2-Column Grid matching game.blade.php spacing -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Personal Information (2/3 width) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="card-glass rounded-3xl p-6 sm:p-8 border border-white/5 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6">Personal Details</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-zinc-400 mb-2 uppercase tracking-wider">Full Name</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', auth()->user()->name) }}"
                                   class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                            @error('name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-semibold text-zinc-500 mb-2 uppercase tracking-wider">Email Address</label>
                            <input type="email" id="email" disabled value="{{ auth()->user()->email }}"
                                   style="background-color: rgba(9, 9, 11, 0.9) !important; color: #71717a !important;"
                                   class="w-full border border-zinc-800/60 rounded-xl px-4 py-3.5 text-sm cursor-not-allowed transition-all focus:outline-none">
                            <p class="text-[10px] text-zinc-500 mt-1.5"><i class="fas fa-lock mr-1"></i> Email cannot be changed for security.</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-zinc-800/80">
                        <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-4">Account Summary</h4>
                        <div class="grid grid-cols-2 gap-4 bg-[#09090b]/30 p-4 rounded-2xl">
                            <div>
                                <p class="text-[10px] text-zinc-500 uppercase tracking-wider">Role Type</p>
                                <p class="text-xs font-bold text-white mt-1">Customer</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-zinc-500 uppercase tracking-wider">Member Since</p>
                                <p class="text-xs font-bold text-white mt-1">{{ auth()->user()->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Password & Action (1/3 width) -->
            <div class="space-y-8">
                <div class="card-glass rounded-3xl p-6 sm:p-8 border border-white/5 shadow-xl flex flex-col justify-between min-h-[300px]">
                    <div>
                        <h3 class="text-lg font-bold text-white mb-6">Security Settings</h3>
                        
                        <div class="space-y-5">
                            <div>
                                <label for="password" class="block text-xs font-semibold text-zinc-400 mb-2 uppercase tracking-wider">New Password</label>
                                <input type="password" name="password" id="password" placeholder="••••••••"
                                       class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                                @error('password')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-zinc-400 mb-2 uppercase tracking-wider">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                                       class="w-full bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all placeholder-zinc-700">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-500/25 btn-glow transition-all active:scale-95 text-sm cursor-pointer">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection