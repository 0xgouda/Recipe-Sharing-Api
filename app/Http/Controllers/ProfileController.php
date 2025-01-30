<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request) {
        $user = $request->user();

        $fav_recipes = [];
        foreach ($user->favorites as $recipe_id) {
            $fav_recipes[] = Recipe::find($recipe_id);
        }

        return response()->json([
            'name' => $user->name, 
            'email' => $user->email, 
            'favorites' => $fav_recipes
        ]);
    }

    public function update(Request $request) {
        $request->validate([
            'name' => 'required_without_all:email,password|max:255|min:1',
            'email' => 'required_without_all:name,password|email|unique:users',
            'password' => 'required_without_all:name,email|confirmed|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ]);
       
        $user = Auth::user();
        $user->update($request->all());

        return response()->json("Successfully updated User profile!");
    }

    public function save(Request $request) {

        $user = $request->user();

        $arr = $user->favorites;
        if (!in_array($request->id, $arr)) {
            $arr[] = $request->id;
            $user->favorites = $arr;
            $user->save();
        }

        return response()->json("Successfully Saved Recipe to Favorites!");
    }
}
