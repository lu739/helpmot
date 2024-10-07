<?php

namespace App\Models;

use App\Enum\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'driver_id',
        'status'
    ];

    public function client() {
        return $this->belongsTo(User::class);
    }

    public function driver() {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query)
    {
        $query
            ->where('status', OrderStatus::ACTIVE->value);
    }
}
