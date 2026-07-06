<x-guest-layout>
    <div class="text-center mb-6">
        <x-application-logo class="mx-auto h-16 w-auto fill-current text-indigo-600" />
        <h1 class="mt-4 text-xl font-semibold text-gray-900">{{ __('Lupa Password') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('Masukkan email Anda dan kami akan kirim tautan reset password.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-primary-button class="w-full justify-center">
                {{ __('Kirim Tautan Reset') }}
            </x-primary-button>
        </div>

        <p class="text-center text-sm text-gray-600">
            <a class="text-indigo-600 hover:text-indigo-900 font-medium" href="{{ route('login') }}">
                {{ __('Kembali ke login') }}
            </a>
        </p>
    </form>
</x-guest-layout>
