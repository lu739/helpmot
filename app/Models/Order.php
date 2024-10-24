<?php

namespace App\Models;

use App\Enum\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'driver_id',
        'status',
        'date_start',
        'date_end',
        'type',
        'location_start',
    ];

    public function client() {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function driver() {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function orderLocation()
    {
        return $this->hasOne(OrderLocation::class)
            ->where('driver_id', $this->driver()->id);
    }

    public function isActive()
    {
        return $this->status === OrderStatus::ACTIVE->value;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', OrderStatus::ACTIVE->value);
    }


    public function scopeHistory(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                OrderStatus::CLIENT_CANCEL->value,
                OrderStatus::DRIVER_CANCEL->value,
                OrderStatus::TIME_EXPIRED->value,
                OrderStatus::COMPLETED_SUCCESSFULLY->value,
            ]);
    }

    public function statusState(): Attribute
    {
        return Attribute::make(
            get: fn () => OrderStatus::from($this->status)->createState($this),
        );
    }
}
