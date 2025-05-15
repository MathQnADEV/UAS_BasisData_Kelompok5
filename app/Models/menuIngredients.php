<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class menuIngredients extends Model
{
    protected $primaryKey = "menu_ingredient_id";
    protected $fillable = [
        'item_id',
        'ingredient_id',
        'qty'
    ];

    public function menuItem(){
        return $this->belongsTo(menuItems::class,'item_id');
    }

    public function ingredient(){
        return $this->belongsTo(ingredients::class,'ingredient_id');
    }
}
