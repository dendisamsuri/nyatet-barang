<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ServiceDetail;
use App\Models\DistanceLog;
use App\Models\FuelLog;
use App\Models\ElectricityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $items = Item::ownedBy($userId)->orderBy('name')->get();

        $period = $request->get('period', 'this_month');
        $itemId = $request->get('item_id');

        $dates = $this->resolveDateRange($period, $request);

        $startDate = $dates['start'];
        $endDate = $dates['end'];

        $itemIds = $this->resolveItemIds($userId, $itemId);

        $totalServiceCost = $this->totalServiceCost($userId, $itemIds, $startDate, $endDate);
        $totalFuelCost = $this->totalFuelCost($userId, $itemIds, $startDate, $endDate);
        $totalFuelLiter = $this->totalFuelLiter($userId, $itemIds, $startDate, $endDate);
        $totalDistance = $this->totalDistance($userId, $itemIds, $startDate, $endDate);
        $totalElectricityCost = $this->totalElectricityCost($userId, $itemIds, $startDate, $endDate);
        $totalAddedKwh = $this->totalAddedKwh($userId, $itemIds, $startDate, $endDate);

        $fuelCostPerKm = $totalDistance > 0 ? $totalFuelCost / $totalDistance : null;
        $kmPerLiter = $totalFuelLiter > 0 ? $totalDistance / $totalFuelLiter : null;
        $electricityCostPerKwh = $totalAddedKwh > 0 ? $totalElectricityCost / $totalAddedKwh : null;
        $totalExpense = $totalServiceCost + $totalFuelCost + $totalElectricityCost;

        $topServiceDetails = $this->topServiceDetails($userId, $itemIds, $startDate, $endDate);
        $topServiceCosts = $this->topServiceCosts($userId, $itemIds, $startDate, $endDate);
        $comparison = collect([
            ['category' => 'Servis', 'amount' => (float) $totalServiceCost],
            ['category' => 'Bensin', 'amount' => (float) $totalFuelCost],
            ['category' => 'Listrik', 'amount' => (float) $totalElectricityCost],
        ]);
        $distancePerDate = $this->distancePerDate($userId, $itemIds, $startDate, $endDate);
        $kwhPerDate = $this->kwhPerDate($userId, $itemIds, $startDate, $endDate);

        return view('dashboard.index', compact(
            'items', 'itemId', 'period', 'startDate', 'endDate',
            'totalServiceCost', 'totalFuelCost', 'totalFuelLiter',
            'totalDistance', 'totalElectricityCost', 'totalAddedKwh',
            'fuelCostPerKm', 'kmPerLiter', 'electricityCostPerKwh',
            'totalExpense', 'topServiceDetails', 'topServiceCosts',
            'comparison', 'distancePerDate', 'kwhPerDate'
        ));
    }

    private function resolveDateRange($period, Request $request)
    {
        if ($period === 'custom') {
            return [
                'start' => $request->get('start_date', now()->startOfMonth()->toDateString()),
                'end' => $request->get('end_date', now()->toDateString()),
            ];
        }

        return [
            'start' => now()->startOfMonth()->toDateString(),
            'end' => now()->toDateString(),
        ];
    }

    private function resolveItemIds($userId, $itemId)
    {
        if ($itemId && $itemId !== 'all') {
            return [Item::ownedBy($userId)->findOrFail($itemId)->id];
        }

        return Item::ownedBy($userId)->pluck('id')->toArray();
    }

    private function totalServiceCost($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return 0;

        return (float) DB::table('service_details')
            ->join('services', 'services.id', '=', 'service_details.service_id')
            ->join('items', 'items.id', '=', 'services.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('services.item_id', $itemIds)
            ->whereBetween('services.service_date', [$startDate, $endDate])
            ->sum('service_details.price');
    }

    private function totalFuelCost($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return 0;

        return (float) DB::table('fuel_logs')
            ->join('items', 'items.id', '=', 'fuel_logs.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('fuel_logs.item_id', $itemIds)
            ->whereBetween('fuel_logs.fuel_date', [$startDate, $endDate])
            ->sum('fuel_logs.amount');
    }

    private function totalFuelLiter($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return 0;

        return (float) DB::table('fuel_logs')
            ->join('items', 'items.id', '=', 'fuel_logs.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('fuel_logs.item_id', $itemIds)
            ->whereBetween('fuel_logs.fuel_date', [$startDate, $endDate])
            ->sum('fuel_logs.liter');
    }

    private function totalDistance($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return 0;

        return (float) DB::table('distance_logs')
            ->join('items', 'items.id', '=', 'distance_logs.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('distance_logs.item_id', $itemIds)
            ->whereBetween('distance_logs.log_date', [$startDate, $endDate])
            ->sum('distance_logs.distance');
    }

    private function totalElectricityCost($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return 0;

        return (float) DB::table('electricity_logs')
            ->join('items', 'items.id', '=', 'electricity_logs.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('electricity_logs.item_id', $itemIds)
            ->whereBetween('electricity_logs.log_date', [$startDate, $endDate])
            ->sum('electricity_logs.amount');
    }

    private function totalAddedKwh($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return 0;

        return (float) DB::table('electricity_logs')
            ->join('items', 'items.id', '=', 'electricity_logs.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('electricity_logs.item_id', $itemIds)
            ->whereBetween('electricity_logs.log_date', [$startDate, $endDate])
            ->selectRaw('COALESCE(SUM(CASE WHEN electricity_logs.purchased_kwh IS NOT NULL THEN electricity_logs.purchased_kwh ELSE electricity_logs.after_kwh - electricity_logs.before_kwh END), 0) as total')
            ->value('total');
    }

    private function topServiceDetails($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return collect();

        return DB::table('service_details')
            ->join('services', 'services.id', '=', 'service_details.service_id')
            ->join('items', 'items.id', '=', 'services.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('services.item_id', $itemIds)
            ->whereBetween('services.service_date', [$startDate, $endDate])
            ->select('service_details.name', DB::raw('COUNT(*) as total_count'))
            ->groupBy('service_details.name')
            ->orderByDesc('total_count')
            ->limit(10)
            ->get();
    }

    private function topServiceCosts($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return collect();

        return DB::table('service_details')
            ->join('services', 'services.id', '=', 'service_details.service_id')
            ->join('items', 'items.id', '=', 'services.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('services.item_id', $itemIds)
            ->whereBetween('services.service_date', [$startDate, $endDate])
            ->select('service_details.name', DB::raw('SUM(service_details.price) as total_cost'))
            ->groupBy('service_details.name')
            ->orderByDesc('total_cost')
            ->limit(10)
            ->get();
    }

    private function distancePerDate($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return collect();

        return DB::table('distance_logs')
            ->join('items', 'items.id', '=', 'distance_logs.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('distance_logs.item_id', $itemIds)
            ->whereBetween('distance_logs.log_date', [$startDate, $endDate])
            ->select('distance_logs.log_date as date', DB::raw('SUM(distance_logs.distance) as total_distance'))
            ->groupBy('distance_logs.log_date')
            ->orderBy('distance_logs.log_date')
            ->get();
    }

    private function kwhPerDate($userId, array $itemIds, $startDate, $endDate)
    {
        if (empty($itemIds)) return collect();

        return DB::table('electricity_logs')
            ->join('items', 'items.id', '=', 'electricity_logs.item_id')
            ->where('items.user_id', $userId)
            ->whereIn('electricity_logs.item_id', $itemIds)
            ->whereBetween('electricity_logs.log_date', [$startDate, $endDate])
            ->select('electricity_logs.log_date as date',
                DB::raw('COALESCE(SUM(CASE WHEN electricity_logs.purchased_kwh IS NOT NULL THEN electricity_logs.purchased_kwh ELSE electricity_logs.after_kwh - electricity_logs.before_kwh END), 0) as total_added_kwh'))
            ->groupBy('electricity_logs.log_date')
            ->orderBy('electricity_logs.log_date')
            ->get();
    }
}
