<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rate;
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
            'ingredients' => $request->ingredients,
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

        if ($recipe != null) {
            $rate = Rate::where('recipe_id',  '=', $recipe->id)->avg('rate');
            $author = User::find($recipe->user_id);
        }

        if ($recipe) {
            return response()->json([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'title' => $recipe->title,
                'ingredients' => $recipe->ingredients,
                'instructions' => $recipe->instructions,
                'rate' => $rate
            ], 200);
        } else {
            return response()->json(["message" => "Recipe Not Found"], 404);
        }
    }

    public function showBy(Request $request) {
        return Recipe::where('user_id', '=', $request->user_id)->get();
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

    public function rate(Request $request) {
        $request->validate([
            'rate' => 'required|integer|in:1,2,3,4,5'
        ]);

        Rate::upsert(
            [
                        'user_id' => $request->user()->id, 
                        'recipe_id' => $request->id, 
                        'rate' => $request->rate
                    ]
        , ['user_id', 'recipe_id'], ['rate']);

        return response()->json('Rating Saved Successfully!');
    }
}