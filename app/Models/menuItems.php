<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class menuItems extends Model{
    protected $table = "menu_items";
    protected $primaryKey = "item_id";
    protected $fillable = [
        "name",
        "category",
        "price",
        "description",
        "is_available"
    ];
    protected $casts = [
        "is_available" => "boolean"
    ];

    public function orderItems(){
        return $this->hasMany(orderItems::class, 'item_id');
    }

    public function menuIngredients(){
        return $this->hasMany(menuIngredients::class,'item_id');
    }
}
