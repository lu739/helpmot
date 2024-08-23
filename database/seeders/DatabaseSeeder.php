<?php

namespace Database\Seeders;

use App\Enum\OrderStatus;
use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
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
            'password' => Hash::make('12345678'),
        ]);

        $maxBusyDrivers = 0;
        foreach (range(1, 4) as $i) {
            $user = User::factory()->create([
                'role' => UserRole::DRIVER->value,
            ]);
            $driver = Driver::factory()->create([
                'user_id' => $user->id,
                'is_activate' => fake()->numberBetween(0, 1),
            ]);
            if ($driver->is_activate) {
                $driver->update([
                    'location_activate' => json_encode([
                        'lot' => round(mt_rand(10 * 100000, 99 * 100000) / 100000, 5),
                        'lat' => round(mt_rand(10 * 100000, 99 * 100000) / 100000, 5),
                    ]),
                ]);
                $maxBusyDrivers++;
            }
        }

        User::factory(10)->create([
            'role' => UserRole::CLIENT->value,
        ]);

        $busyDriversCount = 0;
        foreach (range(1, 20) as $i) {
            $order = Order::factory()->create();
            if ($order->status === OrderStatus::IN_PROGRESS->value) {
                if ($busyDriversCount === $maxBusyDrivers) {
                    $order->update([
                        'status' => OrderStatus::ACTIVE->value
                    ]);
                } else {
                    $order->update([
                        'driver_id' => $busyDriversCount + 1
                    ]);
                    $busyDriversCount++;
                }
            }
        }
    }
}
