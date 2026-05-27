<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Welcome Back!</h2>
        <p class="text-gray-400 mt-2">Login untuk mulai top up & trading</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg text-sm mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                <i class="fas fa-envelope mr-2 text-orange-500"></i>Email
            </label>
            <input 
                wire:model="form.email" 
                id="email" 
                type="email" 
                name="email" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="your@email.com"
                class="input-gaming block w-full px-4 py-3 placeholder-gray-500 @error('form.email') border-red-500 @enderror"
            >
            @error('form.email')
                <p class="text-red-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                <i class="fas fa-lock mr-2 text-orange-500"></i>Password
            </label>
            <div class="relative">
                <input 
                    wire:model="form.password" 
                    id="password" 
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="input-gaming block w-full px-4 py-3 pr-12 placeholder-gray-500 @error('form.password') border-red-500 @enderror"
                >
                <button 
                    type="button" 
                    onclick="togglePassword('password')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition"
                >
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('form.password')
                <p class="text-red-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center cursor-pointer">
                <input 
                    wire:model="form.remember" 
                    id="remember" 
                    type="checkbox" 
                    class="rounded bg-gray-800 border-gray-600 text-orange-600 focus:ring-orange-500"
                >
                <span class="ml-2 text-sm text-gray-400">Remember me</span>
            </label>
            
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                   wire:navigate
                   class="text-sm link-gaming">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button 
            type="submit" 
            wire:loading.attr="disabled"
            class="btn-primary-gaming w-full py-3 px-4 rounded-lg text-white font-semibold text-lg relative"
        >
            <span wire:loading.remove wire:target="login">
                <i class="fas fa-sign-in-alt mr-2"></i>Login Now
            </span>
            <span wire:loading wire:target="login">
                <span class="loading-spinner mr-2"></span>Logging in...
            </span>
        </button>

        <!-- Social Login -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 text-gray-400 bg-transparent">Atau login dengan</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <button 
                type="button" 
                class="btn-secondary-gaming py-3 px-4 rounded-lg text-white font-medium transition"
            >
                <i class="fab fa-google mr-2"></i>Google
            </button>
            <button 
                type="button" 
                class="btn-secondary-gaming py-3 px-4 rounded-lg text-white font-medium transition"
            >
                <i class="fab fa-discord mr-2"></i>Discord
            </button>
        </div>

        <!-- Register Link -->
        <p class="text-center text-gray-400 mt-6">
            Belum punya akun? 
            <a href="{{ route('register') }}" 
               wire:navigate
               class="link-gaming font-semibold">
                Register Now
            </a>
        </p>
    </form>
</div>