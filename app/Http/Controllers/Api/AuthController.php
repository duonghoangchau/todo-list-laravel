<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    
    // POST api/register
    public function register(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'   => 'Đăng ký thàng công',
            'user'      => $user,
            'token'     => $token,
        ], 201);
    }

    // POST api/login
    public function login(Request $request) {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng!'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'  => 'Đăng nhập thành công',
            'user'     => $user,
            'token'    => $token,
        ], 200);
    }

    // GET api/user
    public function user(Request $request) {
        return response()->json($request->user());
    }

    // POST api/logout
    public function logout(Request $request) {

        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'   => 'Đã đăng xuất thành công'], 200);
    }
}
