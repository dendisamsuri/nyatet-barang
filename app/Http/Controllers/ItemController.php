<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::ownedBy(auth()->id())->orderBy('name')->get();

        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Item $item)
    {
        $this->authorizeOwnership($item);

        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $this->authorizeOwnership($item);

        return view('items.edit', compact('item'));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $this->authorizeOwnership($item);

        $item->update($request->validated());

        return redirect()->route('items.index')->with('success', 'Barang berhasil diubah.');
    }

    public function destroy(Item $item)
    {
        $this->authorizeOwnership($item);

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function authorizeOwnership(Item $item): void
    {
        if ($item->user_id !== auth()->id()) {
            abort(404);
        }
    }
}
