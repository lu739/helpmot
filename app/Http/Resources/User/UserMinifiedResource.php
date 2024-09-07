<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMinifiedResource extends JsonResource
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
            'phone' => $this->phone,
            'role' => $this->role,
            'phone_code_expired_datetime' => $this->phone_code_datetime ? Carbon::createFromFormat('Y-m-d H:i:s', $this->phone_code_datetime)->addMinutes(3)->format('Y-m-d H:i:s') : null,
        ];
    }
}
