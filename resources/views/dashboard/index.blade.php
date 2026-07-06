<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <form method="GET" action="{{ route('dashboard') }}" class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Barang</label>
                    <select name="item_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="all" {{ $itemId == 'all' || !$itemId ? 'selected' : '' }}>Semua Barang</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ $itemId == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Periode</label>
                    <select name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" onchange="toggleCustomDate(this)">
                        <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div id="start_date_wrapper" class="{{ $period == 'custom' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div id="end_date_wrapper" class="{{ $period == 'custom' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Tampilkan</button>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Total Servis</p>
                <p class="text-xl font-bold">Rp{{ number_format($totalServiceCost, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Total Bensin</p>
                <p class="text-xl font-bold">Rp{{ number_format($totalFuelCost, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Total Listrik</p>
                <p class="text-xl font-bold">Rp{{ number_format($totalElectricityCost, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Total Pengeluaran</p>
                <p class="text-xl font-bold">Rp{{ number_format($totalExpense, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Total Jarak</p>
                <p class="text-xl font-bold">{{ number_format($totalDistance, 1, ',', '.') }} km</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Estimasi Biaya Bensin/km</p>
                <p class="text-xl font-bold">{{ $fuelCostPerKm !== null ? 'Rp' . number_format($fuelCostPerKm, 2, ',', '.') : '-' }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Estimasi km/liter</p>
                <p class="text-xl font-bold">{{ $kmPerLiter !== null ? number_format($kmPerLiter, 1, ',', '.') : '-' }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <p class="text-sm text-gray-500">Estimasi Biaya Listrik/kWh</p>
                <p class="text-xl font-bold">{{ $electricityCostPerKwh !== null ? 'Rp' . number_format($electricityCostPerKwh, 2, ',', '.') : '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Detail Servis Paling Sering</h3>
                <canvas id="topServiceDetailsChart" height="200"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Total Biaya per Detail Servis</h3>
                <canvas id="topServiceCostsChart" height="200"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Servis vs Bensin vs Listrik</h3>
                <canvas id="comparisonChart" height="200"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Total Jarak per Tanggal</h3>
                <canvas id="distanceChart" height="200"></canvas>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Total kWh Bertambah per Tanggal</h3>
            <canvas id="kwhChart" height="200"></canvas>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleCustomDate(select) {
            document.getElementById('start_date_wrapper').classList.toggle('hidden', select.value !== 'custom');
            document.getElementById('end_date_wrapper').classList.toggle('hidden', select.value !== 'custom');
        }

        const labels1 = @json($topServiceDetails->pluck('name'));
        const data1 = @json($topServiceDetails->pluck('total_count'));
        if (labels1.length) {
            new Chart(document.getElementById('topServiceDetailsChart'), {
                type: 'bar',
                data: { labels: labels1, datasets: [{ label: 'Jumlah', data: data1, backgroundColor: 'rgba(99, 102, 241, 0.5)' }] },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }

        const labels2 = @json($topServiceCosts->pluck('name'));
        const data2 = @json($topServiceCosts->pluck('total_cost'));
        if (labels2.length) {
            new Chart(document.getElementById('topServiceCostsChart'), {
                type: 'bar',
                data: { labels: labels2, datasets: [{ label: 'Total Biaya', data: data2, backgroundColor: 'rgba(239, 68, 68, 0.5)' }] },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }

        const labels3 = @json($comparison->pluck('category'));
        const data3 = @json($comparison->pluck('amount'));
        new Chart(document.getElementById('comparisonChart'), {
            type: 'doughnut',
            data: { labels: labels3, datasets: [{ data: data3, backgroundColor: ['rgba(99, 102, 241, 0.7)', 'rgba(16, 185, 129, 0.7)', 'rgba(245, 158, 11, 0.7)'] }] },
            options: { responsive: true }
        });

        const labels4 = @json($distancePerDate->pluck('date'));
        const data4 = @json($distancePerDate->pluck('total_distance'));
        if (labels4.length) {
            new Chart(document.getElementById('distanceChart'), {
                type: 'line',
                data: { labels: labels4, datasets: [{ label: 'Jarak (km)', data: data4, borderColor: 'rgb(99, 102, 241)', tension: 0.3 }] },
                options: { responsive: true }
            });
        }

        const labels5 = @json($kwhPerDate->pluck('date'));
        const data5 = @json($kwhPerDate->pluck('total_added_kwh'));
        if (labels5.length) {
            new Chart(document.getElementById('kwhChart'), {
                type: 'line',
                data: { labels: labels5, datasets: [{ label: 'kWh', data: data5, borderColor: 'rgb(245, 158, 11)', tension: 0.3 }] },
                options: { responsive: true }
            });
        }
    </script>
    @endpush
</x-app-layout>
