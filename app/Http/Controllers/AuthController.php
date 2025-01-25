<?php

namespace App\Http\Controllers;

use Dotenv\Exception\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request) {
        $request->validate([
            'name' => 'required|max:255|min:1',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ($user->save()) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => 'Successfully Created User!',
                'token' => $token
            ], 201);
        } else {
            return response()->json(["message" => "Error Creating User"], 500);
        }
    }

    public function login(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        $credentials = request()->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $request->user();

        $token = $user->tokens()->delete();            
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['accessToken' => $token, 'tokenType' => 'Bearer'], 200);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json('Successfully logged out');
    }
}