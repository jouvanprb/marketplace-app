<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Create Account</h2>
        <p class="text-gray-400 mt-2">Bergabung dan mulai top up sekarang</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                <i class="fas fa-user mr-2 text-orange-500"></i>Full Name
            </label>
            <input 
                wire:model="name" 
                id="name" 
                type="text"
                required 
                autofocus 
                autocomplete="name"
                placeholder="John Doe"
                class="input-gaming block w-full px-4 py-3 placeholder-gray-500 @error('name') border-red-500 @enderror"
            >
            @error('name')
                <p class="text-red-400 text-sm mt-1 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

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
                autocomplete="username"
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

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                <i class="fas fa-lock mr-2 text-orange-500"></i>Password
            </label>
            <div class="relative">
                <input 
                    wire:model="password" 
                    id="password" 
                    type="password"
                    required 
                    autocomplete="new-password"
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
                    autocomplete="new-password"
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

        <!-- Register Button -->
        <button 
            type="submit"
            wire:loading.attr="disabled"
            class="btn-primary-gaming w-full py-3 px-4 rounded-lg text-white font-semibold text-lg relative"
        >
            <span wire:loading.remove wire:target="register">
                <i class="fas fa-user-plus mr-2"></i>Create Account
            </span>
            <span wire:loading wire:target="register">
                <span class="loading-spinner mr-2"></span>Creating Account...
            </span>
        </button>

        <!-- Social Register -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 text-gray-400 bg-transparent">Atau daftar dengan</span>
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

        <!-- Login Link -->
        <p class="text-center text-gray-400 mt-6">
            Sudah punya akun? 
            <a href="{{ route('login') }}" 
               wire:navigate
               class="link-gaming font-semibold">
                Login Now
            </a>
        </p>
    </form>
</div>