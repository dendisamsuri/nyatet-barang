<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $item->name }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('items.edit', $item) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Edit</a>
                <a href="{{ route('items.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
            <p class="text-gray-500">{{ $item->notes ?? 'Tidak ada catatan.' }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-2">Servis ({{ $item->services->count() }})</h3>
                @forelse ($item->services()->orderByDesc('service_date')->limit(10)->get() as $service)
                    <a href="{{ route('services.show', $service) }}" class="block p-2 hover:bg-gray-50 rounded text-sm">{{ $service->service_date->format('d M Y') }} - Rp{{ number_format($service->total_cost, 0, ',', '.') }}</a>
                @empty
                    <p class="text-sm text-gray-500">Belum ada servis.</p>
                @endforelse
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-2">Statistik Cepat</h3>
                <p class="text-sm">Total Servis: Rp{{ number_format($item->services->load('details')->sum(fn($s) => $s->details->sum('price')), 0, ',', '.') }}</p>
                <p class="text-sm">Total Bensin: Rp{{ number_format($item->fuelLogs->sum('amount'), 0, ',', '.') }}</p>
                <p class="text-sm">Total Listrik: Rp{{ number_format($item->electricityLogs->sum('amount'), 0, ',', '.') }}</p>
                <p class="text-sm">Total Jarak: {{ number_format($item->distanceLogs->sum('distance'), 1, ',', '.') }} km</p>
            </div>
        </div>
    </div>
</x-app-layout>
