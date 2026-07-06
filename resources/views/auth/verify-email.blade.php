<x-guest-layout>
    <div class="text-center mb-6">
        <x-application-logo class="mx-auto h-16 w-auto fill-current text-indigo-600" />
        <h1 class="mt-4 text-xl font-semibold text-gray-900">{{ __('Verifikasi Email') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('Terima kasih telah mendaftar! Sebelum mulai, harap verifikasi email Anda dengan mengklik tautan yang kami kirim.') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 rounded-md px-4 py-3">
            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Kirim Ulang Email Verifikasi') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>
