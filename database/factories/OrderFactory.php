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
            'client_id' => fake()->numberBetween(6, 15),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
