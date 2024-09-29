<?php

namespace App\Http\Resources\Order;

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
                'value' => $this->status->russian(),
            ],
            'type' => [
                'key' => $this->type,
                'value' => $this->type->russian(),
            ],
        ];
    }
}
