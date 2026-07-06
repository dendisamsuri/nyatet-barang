<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Pengisian Bensin</h2>
    </x-slot>

    <div class="py-6 max-w-lg">
        <form method="POST" action="{{ route('fuel-logs.store') }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Barang</label>
                <select name="item_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Pilih Barang</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('item_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="fuel_date" value="{{ old('fuel_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('fuel_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis BBM</label>
                <select name="fuel_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Pilih BBM</option>
                    @foreach ($fuelTypes as $ft)
                        <option value="{{ $ft->id }}" {{ old('fuel_type_id') == $ft->id ? 'selected' : '' }}>{{ $ft->name }}</option>
                    @endforeach
                </select>
                @error('fuel_type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Liter <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="number" name="liter" value="{{ old('liter') }}" step="0.001" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('liter') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <input type="text" name="notes" value="{{ old('notes') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('fuel-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
