<?php

namespace Database\Factories;

use App\Enum\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $statuses = array_map(fn($status) => $status->value, OrderStatus::cases());

        return [
            'status' => fake()->randomElement($statuses),
            'date_start' => now(),
            'location_start' => createLocation(fake()->address()),
            'client_comment' => random_int(0, 1) > 0.5 ? fake()->text() : '',
        ];
    }
}
