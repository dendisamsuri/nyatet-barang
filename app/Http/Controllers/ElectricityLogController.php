<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ElectricityLog;
use App\Http\Requests\StoreElectricityLogRequest;
use App\Http\Requests\UpdateElectricityLogRequest;

class ElectricityLogController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $electricityLogs = ElectricityLog::whereHas('item', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('item')->orderByDesc('log_date')->get();

        return view('electricity_logs.index', compact('electricityLogs'));
    }

    public function create()
    {
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();

        return view('electricity_logs.create', compact('items'));
    }

    public function store(StoreElectricityLogRequest $request)
    {
        Item::ownedBy(auth()->id())->findOrFail($request->item_id);

        ElectricityLog::create($request->validated());

        return redirect()->route('electricity-logs.index')->with('success', 'Catatan token listrik berhasil ditambahkan.');
    }

    public function edit(ElectricityLog $electricityLog)
    {
        $this->authorizeAccess($electricityLog);
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();

        return view('electricity_logs.edit', compact('electricityLog', 'items'));
    }

    public function update(UpdateElectricityLogRequest $request, ElectricityLog $electricityLog)
    {
        $this->authorizeAccess($electricityLog);
        Item::ownedBy(auth()->id())->findOrFail($request->item_id);

        $electricityLog->update($request->validated());

        return redirect()->route('electricity-logs.index')->with('success', 'Catatan token listrik berhasil diubah.');
    }

    public function destroy(ElectricityLog $electricityLog)
    {
        $this->authorizeAccess($electricityLog);

        $electricityLog->delete();

        return redirect()->route('electricity-logs.index')->with('success', 'Catatan token listrik berhasil dihapus.');
    }

    private function authorizeAccess(ElectricityLog $electricityLog): void
    {
        if ($electricityLog->item->user_id !== auth()->id()) {
            abort(404);
        }
    }
}
