<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DistanceLogController;
use App\Http\Controllers\FuelTypeController;
use App\Http\Controllers\FuelLogController;
use App\Http\Controllers\ElectricityLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('items', ItemController::class);

    Route::resource('services', ServiceController::class);

    Route::resource('distance-logs', DistanceLogController::class)->except(['show']);

    Route::resource('fuel-types', FuelTypeController::class)->except(['show']);

    Route::resource('fuel-logs', FuelLogController::class)->except(['show']);

    Route::resource('electricity-logs', ElectricityLogController::class)->except(['show']);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
