<?php

namespace Database\Seeders;

use App\Enum\OrderStatus;
use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'role' => UserRole::ADMIN->value,
            'email' => 'test@test.com',
            'password' => Hash::make('12345678ADMIN'),
        ]);

        // Сидеры водителей
        $busyDrivers = [];;
        foreach (range(1, 10) as $i) {
            $user = User::factory()->create([
                'role' => UserRole::DRIVER->value,
                'password' => Hash::make('12345678DRIVER'),
            ]);

            $driver = Driver::factory()->create([
                'user_id' => $user->id,
                'is_activate' => fake()->numberBetween(0, 1),
            ]);
            if ($driver->is_activate) {
                $driver->update([
                    'location_activate' => createLocation(),
                    'is_busy' => fake()->numberBetween(0, 1),
                ]);
                if ($driver->is_busy) {
                    $busyDrivers[] = $user->id;
                }
            }
        }

        User::factory(14)->create([
            'role' => UserRole::CLIENT->value,
            'password' => Hash::make('12345678CLIENT'),
        ]);


        foreach (range(1, 25) as $i) {
            $order = Order::factory()->create([
                'client_id' => User::query()
                            ->where('role', UserRole::CLIENT->value)
                            ->inRandomOrder()
                            ->first()->id,
            ]);
            if ($order->status === OrderStatus::IN_PROGRESS->value) {
                if (count($busyDrivers) === 0) {
                    $order->update([
                        'status' => OrderStatus::ACTIVE->value
                    ]);
                } else {
                    $order->update([
                        'driver_id' => $busyDrivers[0],
                        'date_start' => now(),
                    ]);
                    array_shift($busyDrivers);
                }
            }
            if ($order->status === OrderStatus::COMPLETED_SUCCESSFULLY->value) {
                $order->update([
                    'date_start' => Carbon::now()->subMinutes(fake()->numberBetween(20, 40)),
                    'date_end' => Carbon::now()->subMinutes(fake()->numberBetween(2, 15)),
                ]);
            }
        }
    }
}
