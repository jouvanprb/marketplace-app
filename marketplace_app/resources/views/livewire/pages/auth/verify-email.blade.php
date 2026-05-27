<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="text-4xl mb-4">
            <i class="fas fa-envelope-open-text text-orange-500"></i>
        </div>
        <h2 class="text-2xl font-bold text-white">Verify Your Email</h2>
        <p class="text-gray-400 mt-2">
            Thanks for signing up! Silakan verifikasi email kamu untuk melanjutkan.
        </p>
    </div>

    <div class="space-y-5">
        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg text-sm">
                <i class="fas fa-check-circle mr-2"></i>
                Link verifikasi baru telah dikirim ke email kamu.
            </div>
        @endif

        <div class="text-center text-gray-400 space-y-4">
            <p>
                <i class="fas fa-info-circle mr-2 text-orange-500"></i>
                Cek inbox atau spam folder email kamu untuk link verifikasi.
            </p>
            
            <div class="flex flex-col space-y-3">
                <button 
                    wire:click="sendVerification"
                    wire:loading.attr="disabled"
                    class="btn-secondary-gaming w-full py-3 px-4 rounded-lg text-white font-semibold"
                >
                    <span wire:loading.remove wire:target="sendVerification">
                        <i class="fas fa-redo mr-2"></i>Resend Verification Email
                    </span>
                    <span wire:loading wire:target="sendVerification">
                        <span class="loading-spinner mr-2"></span>Sending...
                    </span>
                </button>

                <button 
                    wire:click="logout"
                    class="text-gray-400 hover:text-white transition"
                >
                    <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                </button>
            </div>
        </div>
    </div>
</div>