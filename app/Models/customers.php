<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'name'
    ];

    public function orders(){
        return $this->hasMany(orders::class,'customer_id');
    }
}
