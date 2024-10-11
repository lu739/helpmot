<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
      'location_activate',
      'is_activate',
      'is_busy',
    ];

    public function orders() {
        return $this->hasMany(Order::class, 'driver_id','user_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function isActivate()
    {
        return $this->is_activate;
    }
    public function isBusy(): bool
    {
        return $this->is_busy === true;
    }
}
