<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        $logo = Setting::getValue('site_logo');
        $favicon = Setting::getValue('site_favicon');

        return view('settings.index', compact('logo', 'favicon'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
        ]);

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::getValue('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            Setting::setValue('site_logo', $path);
        } elseif ($request->boolean('remove_logo')) {
            $oldLogo = Setting::getValue('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::setValue('site_logo', null);
        }

        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::getValue('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }

            $path = $request->file('favicon')->store('favicons', 'public');
            Setting::setValue('site_favicon', $path);
        } elseif ($request->boolean('remove_favicon')) {
            $oldFavicon = Setting::getValue('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            Setting::setValue('site_favicon', null);
        }

        return redirect()->route('settings.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
