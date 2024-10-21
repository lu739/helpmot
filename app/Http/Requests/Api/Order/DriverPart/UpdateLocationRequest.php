<?php

namespace App\Http\Requests\Api\Order\DriverPart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_location' => ['required', 'array'],
            'last_location.lat' => ['required', 'numeric'],
            'last_location.lot' => ['required', 'numeric'],
            'datetime' => 'required|date_format:Y-m-d H:i:s',
        ];
    }
}
