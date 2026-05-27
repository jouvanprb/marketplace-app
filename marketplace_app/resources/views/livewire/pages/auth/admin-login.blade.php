<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.admin-guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'form.email' => 'These credentials do not match our admin records.',
            ]);
        }

        Session::regenerate();

        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
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
                class="input-minimal block w-full px-3.5 py-2.5 text-sm @error('form.email') border-red-500 @enderror"
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
            <input 
                wire:model="form.password" 
                id="password" 
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                class="input-minimal block w-full px-3.5 py-2.5 text-sm @error('form.password') border-red-500 @enderror"
            >
            @error('form.password')
                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me -->
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
        </div>

        <!-- Login Button -->
        <div class="pt-4 border-t border-[#1f1f22]">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="btn-minimal w-full py-2.5 flex justify-center items-center"
            >
                <span wire:loading.remove wire:target="login">
                    Sign In
                </span>
                <span wire:loading wire:target="login">
                    <i class="fas fa-circle-notch fa-spin mr-2"></i> Signing in...
                </span>
            </button>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" 
               wire:navigate
               class="text-xs text-[#8f8f9d] hover:text-[#ececf1] transition-colors">
                &larr; Back to User Login
            </a>
        </div>
    </form>
</div>
