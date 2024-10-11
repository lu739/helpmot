<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDriverResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'role' => $this->role,
            'email' => $this->email,
            'driver_data' => [
                'id' => $this->driver->id,
                'is_activate' => $this->driver->is_activate,
                'is_busy' => $this->driver->is_busy,
                'location_activate' => $this->driver->location_activate ? json_decode($this->driver->location_activate) : null,
            ],
        ];
    }
}
