<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    protected $primaryKey = 'order_id';
    protected $fillable = [
        "customer_id",
        "employee_id",
        "order_date",
        "total_amount",
        "payment_method",
        "status"
    ];

    public function customer(){
        return $this->belongsTo(customers::class, 'customer_id');
    }

    public function employee(){
        return $this->belongsTo(employees::class, 'employee_id');
    }

    public function orderItems(){
        return $this->hasMany(orderItems::class, 'order_id');
    }
}
