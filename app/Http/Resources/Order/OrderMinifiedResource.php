<?php

namespace App\Http\Resources\Order;

use App\Enum\OrderStatus;
use App\Enum\OrderType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderMinifiedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'driver_id' => $this->driver_id,
            'status' => [
                'key' => $this->status,
                'value' => OrderStatus::from($this->status)->russian(),
            ],
            'type' => [
                'key' => $this->type,
                'value' => OrderType::from($this->type)->russian(),
            ],
        ];
    }
}
