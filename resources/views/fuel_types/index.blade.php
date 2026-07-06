<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Jenis BBM</h2>
            <a href="{{ route('fuel-types.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Tambah BBM</a>
        </div>
    </x-slot>

    <div class="py-6">
        @if ($fuelTypes->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center text-gray-500">Belum ada jenis BBM. Tambah jenis BBM untuk mulai mencatat pengisian bensin.</div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($fuelTypes as $fuelType)
                        <tr>
                            <td class="px-6 py-4">{{ $fuelType->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 text-xs rounded-full {{ $fuelType->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $fuelType->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('fuel-types.edit', $fuelType) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                <form action="{{ route('fuel-types.destroy', $fuelType) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
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
