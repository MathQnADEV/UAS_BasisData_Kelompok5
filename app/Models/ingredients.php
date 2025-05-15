<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ingredients extends Model
{
    protected $primaryKey = "ingredient_id";
    protected $fillable = [
        'name',
        'unit',
        'current_stock',
    ];

    public function menuIngredients(){
        return $this->hasMany(menuIngredients::class, 'ingredient_id');
    }

}
