<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Token Listrik</h2>
    </x-slot>

    <div class="py-6 max-w-lg">
        <form method="POST" action="{{ route('electricity-logs.update', $electricityLog) }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Barang</label>
                <select name="item_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id', $electricityLog->item_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('item_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="log_date" value="{{ old('log_date', $electricityLog->log_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('log_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sisa kWh Sebelum Isi</label>
                <input type="number" name="before_kwh" value="{{ old('before_kwh', $electricityLog->before_kwh) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('before_kwh') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nominal Pembelian (Rp)</label>
                <input type="number" name="amount" value="{{ old('amount', $electricityLog->amount) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">kWh Token/Pembelian <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="number" name="purchased_kwh" value="{{ old('purchased_kwh', $electricityLog->purchased_kwh) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('purchased_kwh') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sisa kWh Setelah Isi</label>
                <input type="number" name="after_kwh" value="{{ old('after_kwh', $electricityLog->after_kwh) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('after_kwh') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <input type="text" name="notes" value="{{ old('notes', $electricityLog->notes) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('electricity-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
