<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::all()->groupBy('group');
        return view('backend.superadmin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            $setting = \App\Models\Setting::where('key', $key)->first();
            if ($setting) {
                if ($request->hasFile($key)) {
                    $path = $request->file($key)->store('settings', 'public');
                    $setting->update(['value' => 'storage/' . $path]);
                    \App\Helpers\ActivityLogger::log('Update Setting', "Updated setting image for: $key");
                } else {
                     $setting->update(['value' => $value]);
                     \App\Helpers\ActivityLogger::log('Update Setting', "Updated setting value for: $key");
                }
            }
        }
        return back()->with('success', 'Pengaturan berhasil disimpan');
    }
}
