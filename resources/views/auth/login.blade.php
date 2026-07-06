<x-guest-layout>
    <div class="text-center mb-6">
        <x-application-logo class="mx-auto h-16 w-auto fill-current text-indigo-600" />
        <h1 class="mt-4 text-xl font-semibold text-gray-900">{{ __('Masuk') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('Silakan masuk untuk melanjutkan') }}</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="Password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-900 font-medium" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div>
            <x-primary-button class="w-full justify-center">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>

        <p class="text-center text-sm text-gray-600">
            {{ __('Belum punya akun?') }}
            <a class="text-indigo-600 hover:text-indigo-900 font-medium" href="{{ route('register') }}">
                {{ __('Daftar') }}
            </a>
        </p>
    </form>
</x-guest-layout>
