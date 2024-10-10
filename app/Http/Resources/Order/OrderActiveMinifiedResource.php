<?php

namespace App\Http\Resources\Order;

use App\Enum\OrderStatus;
use App\Enum\OrderType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderActiveMinifiedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => OrderType::from($this->type)->russian(),
            'location_start' => json_decode($this->location_start),
        ];
    }
}
