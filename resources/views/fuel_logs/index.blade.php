<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengisian Bensin</h2>
            <a href="{{ route('fuel-logs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Tambah Pengisian</a>
        </div>
    </x-slot>

    <div class="py-6">
        @if ($fuelLogs->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center text-gray-500">Belum ada catatan pengisian bensin.</div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">BBM</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nominal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Liter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($fuelLogs as $log)
                        <tr>
                            <td class="px-6 py-4">{{ $log->fuel_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $log->item->name }}</td>
                            <td class="px-6 py-4">{{ $log->fuelType->name }}</td>
                            <td class="px-6 py-4 text-right">Rp{{ number_format($log->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">{{ $log->liter ? number_format($log->liter, 2, ',', '.') . ' L' : '-' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $log->notes ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('fuel-logs.edit', $log) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                <form action="{{ route('fuel-logs.destroy', $log) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
