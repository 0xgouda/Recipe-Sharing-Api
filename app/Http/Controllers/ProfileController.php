<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request) {
        $user = $request->user();
        return response()->json(['name' => $user->name, 'email' => $user->email]);
    }

    public function update(Request $request) {
        $request->validate([
            'name' => 'max:255|min:1',
            'email' => 'email|unique:users',
            'password' => 'confirmed|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ]);
       
        $user = Auth::user();
        $user->update($request->all());

        return response()->json("Successfully updated User profile!");
    }
}
