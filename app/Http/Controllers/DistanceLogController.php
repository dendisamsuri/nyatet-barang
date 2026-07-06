<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\DistanceLog;
use App\Http\Requests\StoreDistanceLogRequest;
use App\Http\Requests\UpdateDistanceLogRequest;

class DistanceLogController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $distanceLogs = DistanceLog::whereHas('item', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('item')->orderByDesc('log_date')->get();

        return view('distance_logs.index', compact('distanceLogs'));
    }

    public function create()
    {
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();

        return view('distance_logs.create', compact('items'));
    }

    public function store(StoreDistanceLogRequest $request)
    {
        Item::ownedBy(auth()->id())->findOrFail($request->item_id);

        DistanceLog::create($request->validated());

        return redirect()->route('distance-logs.index')->with('success', 'Catatan jarak berhasil ditambahkan.');
    }

    public function edit(DistanceLog $distanceLog)
    {
        $this->authorizeAccess($distanceLog);
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();

        return view('distance_logs.edit', compact('distanceLog', 'items'));
    }

    public function update(UpdateDistanceLogRequest $request, DistanceLog $distanceLog)
    {
        $this->authorizeAccess($distanceLog);
        Item::ownedBy(auth()->id())->findOrFail($request->item_id);

        $distanceLog->update($request->validated());

        return redirect()->route('distance-logs.index')->with('success', 'Catatan jarak berhasil diubah.');
    }

    public function destroy(DistanceLog $distanceLog)
    {
        $this->authorizeAccess($distanceLog);

        $distanceLog->delete();

        return redirect()->route('distance-logs.index')->with('success', 'Catatan jarak berhasil dihapus.');
    }

    private function authorizeAccess(DistanceLog $distanceLog): void
    {
        if ($distanceLog->item->user_id !== auth()->id()) {
            abort(404);
        }
    }
}
