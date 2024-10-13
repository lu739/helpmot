<?php

namespace App\Http\Requests\Api\Driver;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ActivateRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'location_activate' => ['required', 'array'],
            'location_activate.lat' => ['required', 'numeric'],
            'location_activate.lot' => ['required', 'numeric'],
        ];
    }
}
