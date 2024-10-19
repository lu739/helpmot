<?php

namespace App\Http\Resources\Order;

use App\Enum\OrderStatus;
use App\Enum\OrderType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCompletedResource extends JsonResource
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
            'client_id' => $this->client_id,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'status' => OrderStatus::from($this->status)->russian(),
            'type' => OrderType::from($this->type)->russian(),
        ];
    }
}
