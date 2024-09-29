<?php

namespace Database\Factories;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->generateUniqueNumericId(),
            'name' => fake()->name(),
            'role' => UserRole::CLIENT->value,
            'phone' => '79' . fake()->numerify('#########'),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

    private function generateUniqueNumericId()
    {
        $randomNumber = mt_rand(1000000, 99999999);

        // Проверка уникальности (рекурсивный вызов, если не уникально)
        while (User::where('id', $randomNumber)->exists()) {
            $randomNumber = mt_rand(1000000, 99999999);
        }

        return $randomNumber;
    }


    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
