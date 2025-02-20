<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
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

        $this->redirectIntended(default: RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('/images/restaurant-bg.jpg');">
    <div class="bg-white/10 backdrop-blur-md p-8 rounded-lg shadow-lg max-w-md w-full border border-white/20">
        <div class="text-center mb-6">
            {{-- <img src="/images/logo.png" alt="Restaurant Logo" class="h-16 mx-auto"> --}}
            <h2 class="text-2xl font-semibold text-white mt-2">Welcome Back!</h2>
            <p class="text-gray-300">Sign in to manage your restaurant.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white" />
                <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full bg-gray-900/70 text-white border-gray-700 rounded-lg focus:ring-yellow-500 focus:border-yellow-500" type="email" name="email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-yellow-300" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-white" />
                <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full bg-gray-900/70 text-white border-gray-700 rounded-lg focus:ring-yellow-500 focus:border-yellow-500"
                                type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-yellow-300" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded bg-gray-900/70 border-gray-700 text-yellow-500 shadow-sm focus:ring-yellow-500" name="remember">
                    <span class="ms-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Forgot Password & Login Button -->
            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="text-sm text-yellow-400 hover:text-yellow-300 transition" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-4 py-2 rounded-lg shadow-md transition">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
