<?php

namespace App\Http\Resources\Order;

use App\Enum\OrderStatus;
use App\Enum\OrderType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'date_start' => $this->date_start,
            'status' => OrderStatus::from($this->status)->russian(),
            'type' => OrderType::from($this->type)->russian(),
            'location_start' => json_decode($this->location_start),
            'client_comment' => $this->client_comment,
        ];
    }
}
