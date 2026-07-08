<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan Tampilan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Logo</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo Saat Ini</label>
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center">
                            @if ($logo)
                                <img src="{{ Storage::url($logo) }}" alt="Logo" class="block h-16 w-auto">
                            @else
                                <x-application-logo class="block h-16 w-auto fill-current text-gray-800" />
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="logo" class="block text-sm font-medium text-gray-700">Upload Logo Baru</label>
                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF, SVG. Maksimal 2MB.</p>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($logo)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-sm text-gray-700">Hapus logo (kembali ke logo default)</span>
                            </label>
                        </div>
                    @endif
                </div>

                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Favicon</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Favicon Saat Ini</label>
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center">
                            @if ($favicon)
                                <img src="{{ Storage::url($favicon) }}" alt="Favicon" class="block h-10 w-auto">
                            @else
                                <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded text-indigo-600 text-xs font-bold">
                                    NB
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="favicon" class="block text-sm font-medium text-gray-700">Upload Favicon Baru</label>
                        <input type="file" name="favicon" id="favicon" accept="image/*,.ico"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF, SVG, ICO. Maksimal 1MB.</p>
                        @error('favicon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($favicon)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="remove_favicon" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-sm text-gray-700">Hapus favicon (kembali ke default)</span>
                            </label>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
