<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLocation extends Model
{
    protected $fillable = [
        'order_id',
        'driver_id',
        'last_location',
        'start_location',
        'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
