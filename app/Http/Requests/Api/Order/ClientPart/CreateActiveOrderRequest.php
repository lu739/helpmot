<?php

namespace App\Http\Requests\Api\Order\ClientPart;

use App\Enum\OrderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateActiveOrderRequest extends FormRequest
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
            'location_start' => ['required', 'array'],
            'location_start.lat' => ['required', 'numeric'],
            'location_start.lot' => ['required', 'numeric'],
            'location_start.address' => ['required', 'string'],
            'type' => ['required', new Enum(OrderType::class)],
            'comment' => ['nullable', 'string'],
        ];
    }
}
