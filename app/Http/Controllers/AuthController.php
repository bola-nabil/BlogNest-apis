<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token, 'user' => $user], 201);
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if(!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invaild Credentials'], 401);
        }

        return response()->json(['token' => $token, 'user' => auth()->user()], 201);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            "current_password" => "required",
            "new_password" => "required|min:8|confirmed"
        ]);

        $user = $request->user();

        if(!Hash::check($request->current_password, $request->password)) {
            return response()->json(["message" => "Current password is incorrect."], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(data: ["message" => "Password changed successfully."]);
    }
}
