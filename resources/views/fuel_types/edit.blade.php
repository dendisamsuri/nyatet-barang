<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Jenis BBM</h2>
    </x-slot>

    <div class="py-6 max-w-lg">
        <form method="POST" action="{{ route('fuel-types.update', $fuelType) }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama BBM</label>
                <input type="text" name="name" value="{{ old('name', $fuelType->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $fuelType->is_active) ? 'checked' : '' }} class="rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <label class="text-sm font-medium text-gray-700">Aktif</label>
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('fuel-types.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
