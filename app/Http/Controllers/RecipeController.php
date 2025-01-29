<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|max:255|min:1',
            'ingredients' => 'required|array',
            'instructions' => 'required'
        ]);

        $recipe = $request->user()->recipes()->create([
            'title' => $request->title,
            'ingredients' => json_encode($request->ingredients, JSON_PRETTY_PRINT),
            'instructions' => $request->instructions
        ]);

        if ($recipe->save()) {
            return response()->json([
                'Successfully Published Recipe!'
            ], 201);
        } else {
            return response()->json(['message' => 'Error Creating Recipe'], 500);
        }
    }

    public function show(Request $request) {
        $recipe = Recipe::find($request->id);

        if ($recipe) {
            return response()->json([
                'title' => $recipe->title,
                'ingredients' => json_decode($recipe->ingredients),
                'instructions' => $recipe->instructions
            ], 200);
        } else {
            return response()->json(["message" => "Recipe Not Found"], 404);
        }
    }

    public function search(Request $request) {
        $request->validate([
            'title' => ['required_without_all:instructions,ingredients', 'string', 'max:255', 'min:1'],
            'instructions' => ['required_without_all:title,ingredients', 'string', 'max:255', 'min:1'],
            'ingredients' => ['required_without_all:title,instructions', 'string', 'max:255', 'min:1']
        ]);

        return Recipe::where([
            ['title', 'like', "%$request->title%"],
            ['instructions', 'like', "%$request->instructions%"],
            ['ingredients', 'like', "%$request->ingredients%"]
        ])->limit(100)->get();
    }
}