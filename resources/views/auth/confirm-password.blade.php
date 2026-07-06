<x-guest-layout>
    <div class="text-center mb-6">
        <x-application-logo class="mx-auto h-16 w-auto fill-current text-indigo-600" />
        <h1 class="mt-4 text-xl font-semibold text-gray-900">{{ __('Konfirmasi Password') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('Ini adalah area aman, harap konfirmasi password Anda sebelum melanjutkan.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-primary-button class="w-full justify-center">
                {{ __('Konfirmasi') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
