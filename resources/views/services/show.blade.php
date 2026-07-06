<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Servis</h2>
            <div class="flex gap-2">
                <a href="{{ route('services.edit', $service) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Edit</a>
                <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-2xl">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Barang</p>
                    <p class="font-medium">{{ $service->item->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Servis</p>
                    <p class="font-medium">{{ $service->service_date->format('d M Y') }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Catatan</p>
                    <p>{{ $service->notes ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($service->details as $detail)
                    <tr>
                        <td class="px-6 py-4">{{ $detail->name }}</td>
                        <td class="px-6 py-4 text-right">Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $detail->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada detail biaya.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td class="px-6 py-3">Total</td>
                        <td class="px-6 py-3 text-right">Rp{{ number_format($service->total_cost, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-app-layout>
