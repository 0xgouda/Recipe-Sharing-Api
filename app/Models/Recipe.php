<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Recipe extends Model
{
    protected $fillable = ['title', 'instructions', 'ingredients'];
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function rates() {
        return $this->hasMany(Rate::class);
    }

    public function casts() {
        return [
            'ingredients' => 'array'
        ];
    }
}
