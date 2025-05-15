<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orderItems extends Model
{
    protected $primaryKey = 'order_item_id';
    protected $fillable = [
        "order_id",
        "item_id",
        "qty",
        "special_req",
        "price_at_order",
    ];

    public function order(){
        return $this->belongsTo(orders::class, 'order_id');
    }

    public function menuItem(){
        return $this->belongsTo(menuItems::class,'item_id');
    }
}
