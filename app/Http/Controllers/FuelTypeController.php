<?php

namespace App\Http\Controllers;

use App\Models\FuelType;
use App\Http\Requests\StoreFuelTypeRequest;
use App\Http\Requests\UpdateFuelTypeRequest;

class FuelTypeController extends Controller
{
    public function index()
    {
        $fuelTypes = FuelType::ownedBy(auth()->id())->orderBy('name')->get();

        return view('fuel_types.index', compact('fuelTypes'));
    }

    public function create()
    {
        return view('fuel_types.create');
    }

    public function store(StoreFuelTypeRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        FuelType::create($data);

        return redirect()->route('fuel-types.index')->with('success', 'Jenis BBM berhasil ditambahkan.');
    }

    public function edit(FuelType $fuelType)
    {
        $this->authorizeOwnership($fuelType);

        return view('fuel_types.edit', compact('fuelType'));
    }

    public function update(UpdateFuelTypeRequest $request, FuelType $fuelType)
    {
        $this->authorizeOwnership($fuelType);

        $fuelType->update($request->validated());

        return redirect()->route('fuel-types.index')->with('success', 'Jenis BBM berhasil diubah.');
    }

    public function destroy(FuelType $fuelType)
    {
        $this->authorizeOwnership($fuelType);

        if ($fuelType->fuelLogs()->exists()) {
            return redirect()->route('fuel-types.index')
                ->with('error', 'Jenis BBM sudah pernah dipakai. Nonaktifkan saja.');
        }

        $fuelType->delete();

        return redirect()->route('fuel-types.index')->with('success', 'Jenis BBM berhasil dihapus.');
    }

    private function authorizeOwnership(FuelType $fuelType): void
    {
        if ($fuelType->user_id !== auth()->id()) {
            abort(404);
        }
    }
}
