<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $items = Item::ownedBy($userId)->orderBy('name')->get();

        return view('reports.index', compact('items'));
    }
}
