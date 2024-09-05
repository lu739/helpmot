<?php

namespace App\Http\Resources\OnboardingUser;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OnboardingClientResource extends JsonResource
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
            'has_phone_code' => (bool) $this->phone_code,
            'phone_code_expired_datetime' => Carbon::createFromFormat('Y-m-d H:i:s', $this->phone_code_datetime)->addMinutes(3)->format('Y-m-d H:i:s'),
        ];
    }
}
