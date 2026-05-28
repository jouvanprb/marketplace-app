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
        
        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-[#8f8f9d] mb-2 uppercase tracking-wider">
                Full Name
            </label>
            <input 
                wire:model="name" 
                id="name" 
                type="text"
                required 
                autofocus 
                autocomplete="name"
                class="input-gaming block w-full px-3.5 py-2.5 text-sm @error('name') border-red-500 @enderror"
            >
            @error('name')
                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-[#8f8f9d] mb-2 uppercase tracking-wider">
                Email Address
            </label>
            <input 
                wire:model="email" 
                id="email" 
                type="email"
                required 
                autocomplete="username"
                class="input-gaming block w-full px-3.5 py-2.5 text-sm @error('email') border-red-500 @enderror"
            >
            @error('email')
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
                    wire:model="password" 
                    id="password" 
                    type="password"
                    required 
                    autocomplete="new-password"
                    class="input-gaming block w-full px-3.5 py-2.5 text-sm pr-10 @error('password') border-red-500 @enderror"
                >
                <button 
                    type="button" 
                    onclick="togglePassword('password')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition"
                >
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-[#8f8f9d] mb-2 uppercase tracking-wider">
                Confirm Password
            </label>
            <div class="relative">
                <input 
                    wire:model="password_confirmation" 
                    id="password_confirmation" 
                    type="password"
                    required 
                    autocomplete="new-password"
                    class="input-gaming block w-full px-3.5 py-2.5 text-sm pr-10 @error('password_confirmation') border-red-500 @enderror"
                >
                <button 
                    type="button" 
                    onclick="togglePassword('password_confirmation')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition"
                >
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="text-red-500 text-xs mt-1.5 flex items-center">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Register Button -->
        <div class="pt-4 border-t border-[#1f1f22]">
            <button 
                type="submit"
                wire:loading.attr="disabled"
                class="btn-primary-gaming w-full py-2.5 flex justify-center items-center"
            >
                <span wire:loading.remove wire:target="register">
                    Create Account
                </span>
                <span wire:loading wire:target="register">
                    <i class="fas fa-circle-notch fa-spin mr-2"></i> Creating...
                </span>
            </button>
        </div>

        <!-- Social Register -->
        <div class="relative my-5">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-[#1f1f22]"></div>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <p class="text-xs text-[#8f8f9d]">
                Already have an account? 
                <a href="{{ route('login') }}" 
                   wire:navigate
                   class="link-gaming font-semibold">
                    Sign In
                </a>
            </p>
        </div>
    </form>
</div>