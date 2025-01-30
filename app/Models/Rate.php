<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected function user() {
        return $this->belongsTo(User::class);
    }

    protected function recipe() {
        return $this->belongsTo(Recipe::class);
    }
}
