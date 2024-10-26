<?php

namespace App\Http\Resources\Order;

use App\Enum\OrderStatus;
use App\Enum\OrderType;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderInProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => OrderStatus::from($this->status)->russian(),
            'type' => OrderType::from($this->type)->russian(),
            'location_start' => json_decode($this->location_start),
            'client_comment' => $this->client_comment,
            'date_start' => $this->date_start,
            'driver' => UserResource::make($this->driver),
            'client' => UserResource::make($this->client),
            'order_location' => json_decode($this->order_location),
        ];
    }
}
