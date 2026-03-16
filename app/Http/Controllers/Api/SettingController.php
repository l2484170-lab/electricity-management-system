<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(SystemSetting::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:system_settings,key',
            'value' => 'required|string',
        ]);

        $setting = SystemSetting::create($request->only(['key', 'value', 'description']));
        return response()->json($setting, 201);
    }

    public function update(Request $request, string $key)
    {
        $setting = SystemSetting::where('key', $key)->firstOrFail();
        $setting->update(['value' => $request->value, 'updated_at' => now()]);
        return response()->json($setting);
    }

    public function show(string $key)
    {
        return response()->json(SystemSetting::where('key', $key)->firstOrFail());
    }
}
