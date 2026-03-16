<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all();
        $users = User::all();
        return view('settings.index', compact('settings', 'users'));
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $key => $value) {
            SystemSetting::where('key', $key)->update(['value' => $value, 'updated_at' => now()]);
        }
        return back()->with('success', 'تم تحديث الإعدادات');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,accountant,meter_reader,customer_service',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return back()->with('success', 'تم إنشاء المستخدم');
    }
}
