<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return $this->is_activate;
    }
}
