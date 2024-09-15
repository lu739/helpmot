<?php

namespace Tests\Feature\Driver;

use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GetDriverTest extends TestCase
{
    private Driver $driver;
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => UserRole::DRIVER->value,
        ]);

        $this->driver = Driver::factory()
            ->create([
                'is_activate' => true,
                'user_id' => $user->id,
            ]);

        if ($this->driver->is_activate) {
            $this->driver->update([
                'location_activate' => json_encode([
                    'lot' => round(mt_rand(10 * 100000, 99 * 100000) / 100000, 5),
                    'lat' => round(mt_rand(10 * 100000, 99 * 100000) / 100000, 5),
                ]),
            ]);
        }

        $this->actingAs($user);
    }

    public function test_get_driver(): void
    {
        $response = $this->get(route('driver.show', $this->driver));

        $response->assertOk();
        $data = $response->json('data');
        $response->assertJsonStructure([
            'data' => [
                'id',
                'is_activate',
                'location_activate',
                'name',
                'phone',
            ]
        ]);

        $response->assertJson([
            'data' => [
                'id' => $this->driver->id,
                'name' => $this->driver->user->name,
                'is_activate' => (int)$this->driver->is_activate,
                'phone' => $this->driver->user->phone,
            ]
        ]);

        if ((bool)$data['is_activate']) {
            $this->assertNotNull($data['location_activate']);
            $this->assertArrayHasKey('lat', $data['location_activate']);
            $this->assertArrayHasKey('lot', $data['location_activate']);
        } else {
            $this->assertNull($data['location_activate']);
        }
    }
}
