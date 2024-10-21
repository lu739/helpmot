<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_activate',
        'is_activate',
        'is_busy',
    ];

    public function orders(): HasMany {
        return $this->hasMany(Order::class, 'driver_id', 'user_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function activeOrderLocation()
    {
        return $this->hasOne(OrderLocation::class, 'driver_id', 'user_id');
    }

    public function isActivate(): bool
    {
        return $this->is_activate === 1;
    }
    public function isBusy(): bool
    {
        return $this->is_busy === true;
    }
}
