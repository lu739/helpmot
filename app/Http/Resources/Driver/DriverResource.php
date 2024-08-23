<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'is_activate' => $this->is_activate,
            'location_activate' => $this->location_activate ? json_decode($this->location_activate) : null,
            'name' => $this->user->name,
            'phone' => $this->user->phone,
        ];
    }
}
