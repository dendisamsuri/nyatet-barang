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
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/manifest.json', function () {
    $favicon = \App\Models\Setting::getValue('site_favicon');

    if ($favicon) {
        $url = \Illuminate\Support\Facades\Storage::url($favicon);
        $icons = [
            [
                'src' => $url,
                'sizes' => 'any',
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ],
        ];
    } else {
        $icons = [
            [
                'src' => '/app-icons/icon-192.svg',
                'sizes' => '192x192',
                'type' => 'image/svg+xml',
            ],
            [
                'src' => '/app-icons/icon-512.svg',
                'sizes' => '512x512',
                'type' => 'image/svg+xml',
                'purpose' => 'any',
            ],
            [
                'src' => '/app-icons/icon-512.svg',
                'sizes' => '512x512',
                'type' => 'image/svg+xml',
                'purpose' => 'maskable',
            ],
        ];
    }

    return response()->json([
        'name' => config('app.name', 'Nyatet Barang'),
        'short_name' => 'Nyatet',
        'description' => 'Aplikasi pencatatan barang, servis, dan pengeluaran kendaraan',
        'start_url' => '/',
        'display' => 'standalone',
        'background_color' => '#f3f4f6',
        'theme_color' => '#4f46e5',
        'icons' => $icons,
    ]);
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

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
