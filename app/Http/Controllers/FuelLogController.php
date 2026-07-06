<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\FuelLog;
use App\Models\FuelType;
use App\Http\Requests\StoreFuelLogRequest;
use App\Http\Requests\UpdateFuelLogRequest;

class FuelLogController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $fuelLogs = FuelLog::whereHas('item', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('item', 'fuelType')->orderByDesc('fuel_date')->get();

        return view('fuel_logs.index', compact('fuelLogs'));
    }

    public function create()
    {
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();
        $fuelTypes = FuelType::ownedBy(auth()->id())->active()->orderBy('name')->get();

        return view('fuel_logs.create', compact('items', 'fuelTypes'));
    }

    public function store(StoreFuelLogRequest $request)
    {
        Item::ownedBy(auth()->id())->findOrFail($request->item_id);
        FuelType::ownedBy(auth()->id())->findOrFail($request->fuel_type_id);

        FuelLog::create($request->validated());

        return redirect()->route('fuel-logs.index')->with('success', 'Pengisian bensin berhasil ditambahkan.');
    }

    public function edit(FuelLog $fuelLog)
    {
        $this->authorizeAccess($fuelLog);
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();
        $fuelTypes = FuelType::ownedBy(auth()->id())->active()->orderBy('name')->get();

        return view('fuel_logs.edit', compact('fuelLog', 'items', 'fuelTypes'));
    }

    public function update(UpdateFuelLogRequest $request, FuelLog $fuelLog)
    {
        $this->authorizeAccess($fuelLog);
        Item::ownedBy(auth()->id())->findOrFail($request->item_id);
        FuelType::ownedBy(auth()->id())->findOrFail($request->fuel_type_id);

        $fuelLog->update($request->validated());

        return redirect()->route('fuel-logs.index')->with('success', 'Pengisian bensin berhasil diubah.');
    }

    public function destroy(FuelLog $fuelLog)
    {
        $this->authorizeAccess($fuelLog);

        $fuelLog->delete();

        return redirect()->route('fuel-logs.index')->with('success', 'Pengisian bensin berhasil dihapus.');
    }

    private function authorizeAccess(FuelLog $fuelLog): void
    {
        if ($fuelLog->item->user_id !== auth()->id()) {
            abort(404);
        }
    }
}
