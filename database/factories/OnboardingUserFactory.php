<?php

namespace Database\Factories;

use App\Enum\UserRole;
use App\Models\OnboardingUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class OnboardingUserFactory extends Factory
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
            'phone' => '79' . fake()->numerify('#########'),
            'password' => static::$password ??= Hash::make('password'),
            'role' => UserRole::CLIENT->value,
        ];
    }

    private function generateUniqueNumericId()
    {
        $randomNumber = mt_rand(1000000, 99999999);

        // Проверка уникальности (рекурсивный вызов, если не уникально)
        while (OnboardingUser::where('id', $randomNumber)->exists()) {
            $randomNumber = mt_rand(1000000, 99999999);
        }

        return $randomNumber;
    }
}
