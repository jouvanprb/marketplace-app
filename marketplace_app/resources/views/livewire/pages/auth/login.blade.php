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

         if (auth()->user()->isAdmin()) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('home', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Session Status -->
    @if (session('status'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-md text-sm mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-[#8f8f9d] mb-2 uppercase tracking-wider">
                Email Address
            </label>
            <input 
                wire:model="form.email" 
                id="email" 
                type="email" 
                name="email" 
                required 
                autofocus 
                autocomplete="username"
                class="input-gaming block w-full px-3.5 py-2.5 text-sm @error('form.email') border-red-500 @enderror"
            >
            @error('form.email')
                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-[#8f8f9d] mb-2 uppercase tracking-wider">
                Password
            </label>
            <div class="relative">
                <input 
                    wire:model="form.password" 
                    id="password" 
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    class="input-gaming block w-full px-3.5 py-2.5 text-sm pr-10 @error('form.password') border-red-500 @enderror"
                >
                <button 
                    type="button" 
                    onclick="togglePassword('password')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition"
                >
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('form.password')
                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="flex items-center cursor-pointer">
                <input 
                    wire:model="form.remember" 
                    id="remember" 
                    type="checkbox" 
                    class="rounded bg-[#0a0a0a] border-[#1f1f22] text-[#ea580c] focus:ring-[#ea580c]"
                >
                <span class="ml-2.5 text-xs text-[#8f8f9d]">Remember me</span>
            </label>
            
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                   wire:navigate
                   class="text-xs link-gaming">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="pt-4 border-t border-[#1f1f22]">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="btn-primary-gaming w-full py-2.5 flex justify-center items-center"
            >
                <span wire:loading.remove wire:target="login">
                    Sign In
                </span>
                <span wire:loading wire:target="login">
                    <i class="fas fa-circle-notch fa-spin mr-2"></i> Signing in...
                </span>
            </button>
        </div>

        <!-- Social Login -->
        <div class="relative my-5">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-[#1f1f22]"></div>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-6">
            <p class="text-xs text-[#8f8f9d]">
                Don't have an account? 
                <a href="{{ route('register') }}" 
                   wire:navigate
                   class="link-gaming font-semibold">
                    Register Now
                </a>
            </p>
        </div>
    </form>
</div>