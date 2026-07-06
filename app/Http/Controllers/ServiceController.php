<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $services = Service::whereHas('item', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('item', 'details')->orderByDesc('service_date')->get();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();

        return view('services.create', compact('items'));
    }

    public function store(StoreServiceRequest $request)
    {
        $this->validateItemOwnership($request->item_id);

        $service = DB::transaction(function () use ($request) {
            $service = Service::create($request->safe()->except('details'));

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $service->details()->create($detail);
                }
            }

            return $service;
        });

        return redirect()->route('services.index')->with('success', 'Servis berhasil ditambahkan.');
    }

    public function show(Service $service)
    {
        $this->authorizeServiceAccess($service);
        $service->load('item', 'details');

        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $this->authorizeServiceAccess($service);
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();
        $service->load('details');

        return view('services.edit', compact('service', 'items'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $this->authorizeServiceAccess($service);
        $this->validateItemOwnership($request->item_id);

        DB::transaction(function () use ($request, $service) {
            $service->update($request->safe()->except('details'));

            $service->details()->delete();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $service->details()->create($detail);
                }
            }
        });

        return redirect()->route('services.index')->with('success', 'Servis berhasil diubah.');
    }

    public function destroy(Service $service)
    {
        $this->authorizeServiceAccess($service);

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Servis berhasil dihapus.');
    }

    private function authorizeServiceAccess(Service $service): void
    {
        if ($service->item->user_id !== auth()->id()) {
            abort(404);
        }
    }

    private function validateItemOwnership(int $itemId): void
    {
        Item::ownedBy(auth()->id())->findOrFail($itemId);
    }
}
