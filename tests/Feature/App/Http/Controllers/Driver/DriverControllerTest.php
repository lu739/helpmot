<?php

namespace Tests\Feature\App\Http\Controllers\Driver;

use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DriverControllerTest extends TestCase
{
    use RefreshDatabase;

    private Driver $driver;
    protected function setUp(): void
    {
        parent::setUp();

        foreach (range(1, 5) as $userId) {
            Driver::factory()->create([
                'user_id' => $userId,
            ]);
        }

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => UserRole::DRIVER->value,
        ]);

        $this->driver = Driver::factory()
            ->create([
                'is_activate' => true,
                'user_id' => $this->user->id,
            ]);

        if ($this->driver->is_activate) {
            $this->driver->update([
                'location_activate' => createLocation(),
            ]);
        }
    }

    /**
     * A basic feature test example.
     */
    public function test_get_drivers(): void
    {
        $response = $this->get(route('drivers.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'is_activate',
                    'location_activate' => ['lat', 'lot'],
                    'name',
                    'phone',
                ]
            ]
        ]);
    }

    public function test_get_driver(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('drivers.show', $this->driver));

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
