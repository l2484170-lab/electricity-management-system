<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Incorrect username or password'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Inactive user'], 400);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,accountant,meter_reader,customer_service',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
    }

    public function listUsers()
    {
        return response()->json(User::all());
    }

    public function updateUser(Request $request, int $userId)
    {
        $user = User::findOrFail($userId);
        $user->update($request->only(['full_name', 'email', 'role', 'is_active']));
        return response()->json($user);
    }
}
