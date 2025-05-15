<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employees extends Model
{
    protected $primaryKey = "employee_id";
    protected $fillable = [
        "name",
        "position"
    ];

    public function orders(){
        return $this->hasMany(Orders::class,'employee_id');
    }
}
