<?php

namespace App\Http\Resources\Order;

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
            'status' =>  $this->status->russian(),
            'type' => $this->type,
            'location_start' => json_decode($this->location_start),
            'client_comment' => $this->client_comment,
        ];
    }
}
