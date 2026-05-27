<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="text-4xl mb-4">
            <i class="fas fa-key text-orange-500"></i>
        </div>
        <h2 class="text-2xl font-bold text-white">Forgot Password?</h2>
        <p class="text-gray-400 mt-2">
            Don't worry! Masukkan email kamu dan kami akan kirim link reset password.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg text-sm mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                <i class="fas fa-envelope mr-2 text-orange-500"></i>Email Address
            </label>
            <input 
                wire:model="email" 
                id="email" 
                type="email" 
                required 
                autofocus
                placeholder="your@email.com"
                class="input-gaming block w-full px-4 py-3 placeholder-gray-500 @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="text-red-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            wire:loading.attr="disabled"
            class="btn-primary-gaming w-full py-3 px-4 rounded-lg text-white font-semibold text-lg relative"
        >
            <span wire:loading.remove wire:target="sendPasswordResetLink">
                <i class="fas fa-paper-plane mr-2"></i>Send Reset Link
            </span>
            <span wire:loading wire:target="sendPasswordResetLink">
                <span class="loading-spinner mr-2"></span>Sending...
            </span>
        </button>

        <!-- Back to Login -->
        <p class="text-center text-gray-400 mt-6">
            <a href="{{ route('login') }}" 
               wire:navigate
               class="link-gaming font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Back to Login
            </a>
        </p>
    </form>
</div>