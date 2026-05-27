<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->input('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', __($status));
        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="text-4xl mb-4">
            <i class="fas fa-shield-alt text-orange-500"></i>
        </div>
        <h2 class="text-2xl font-bold text-white">Reset Password</h2>
        <p class="text-gray-400 mt-2">Buat password baru untuk akun kamu</p>
    </div>

    <form wire:submit="resetPassword" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                <i class="fas fa-envelope mr-2 text-orange-500"></i>Email
            </label>
            <input 
                wire:model="email" 
                id="email" 
                type="email" 
                required 
                autofocus
                class="input-gaming block w-full px-4 py-3 placeholder-gray-500 @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="text-red-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                <i class="fas fa-lock mr-2 text-orange-500"></i>New Password
            </label>
            <div class="relative">
                <input 
                    wire:model="password" 
                    id="password" 
                    type="password"
                    required 
                    placeholder="Min. 8 characters"
                    class="input-gaming block w-full px-4 py-3 pr-12 placeholder-gray-500 @error('password') border-red-500 @enderror"
                >
                <button 
                    type="button" 
                    onclick="togglePassword('password')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition"
                >
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="text-red-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                <i class="fas fa-check-circle mr-2 text-orange-500"></i>Confirm Password
            </label>
            <div class="relative">
                <input 
                    wire:model="password_confirmation" 
                    id="password_confirmation" 
                    type="password"
                    required 
                    placeholder="Repeat password"
                    class="input-gaming block w-full px-4 py-3 pr-12 placeholder-gray-500 @error('password_confirmation') border-red-500 @enderror"
                >
                <button 
                    type="button" 
                    onclick="togglePassword('password_confirmation')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition"
                >
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
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
            <span wire:loading.remove wire:target="resetPassword">
                <i class="fas fa-save mr-2"></i>Reset Password
            </span>
            <span wire:loading wire:target="resetPassword">
                <span class="loading-spinner mr-2"></span>Resetting...
            </span>
        </button>
    </form>
</div>