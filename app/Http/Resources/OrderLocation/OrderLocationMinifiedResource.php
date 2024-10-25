<?php

namespace App\Http\Resources\OrderLocation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderLocationMinifiedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'datetime' => $this->datetime,
            'last_location' => json_decode($this->last_location),
        ];
    }
}
