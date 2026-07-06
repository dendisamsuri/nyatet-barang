<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Servis</h2>
    </x-slot>

    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('services.store') }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-4">
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
                <label class="block text-sm font-medium text-gray-700">Tanggal Servis</label>
                <input type="date" name="service_date" value="{{ old('service_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('service_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan Servis</label>
                <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Detail Biaya</label>
                    <button type="button" onclick="addDetail()" class="text-sm text-indigo-600 hover:text-indigo-900">+ Tambah Detail</button>
                </div>
                <div id="details_container">
                </div>
                @error('details') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        let detailIndex = 0;
        function addDetail(name = '', price = '', notes = '') {
            const container = document.getElementById('details_container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-start mb-2 p-2 border rounded';
            div.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="details[${detailIndex}][name]" value="${name}" placeholder="Nama detail" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </div>
                <div class="w-32">
                    <input type="number" name="details[${detailIndex}][price]" value="${price}" placeholder="Harga" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </div>
                <div class="flex-1">
                    <input type="text" name="details[${detailIndex}][notes]" value="${notes}" placeholder="Catatan" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
            `;
            container.appendChild(div);
            detailIndex++;
        }
    </script>
    @endpush
</x-app-layout>
