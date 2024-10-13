<?php

namespace Tests\Feature\App\Http\Controllers\Driver;

use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ActivateDriverControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Driver $driver;
    private string $location;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => UserRole::DRIVER->value,
        ]);

        $this->location = createLocation();

        $this->driver = Driver::factory()
            ->create([
                'is_activate' => false,
                'user_id' => $this->user->id,
            ]);

        $token = $this->user->createToken('test-token')->plainTextToken;

        Sanctum::actingAs($this->user, ['*']);
        $this->withHeader('Authorization', "Bearer $token");
    }

    public function test_driver_can_activate(): void
    {
        $response = $this->post(route(
            'driver.activate',
            $this->driver->id
        ),
            ['location_activate' => json_decode($this->location, true)]
        );

        $this->driver->refresh();

        $response->assertOk();
        $this->assertEquals($this->driver->is_activate, 1);
        $this->assertEquals($this->driver->location_activate, $this->location);
        $this->assertNotNull($this->driver->location_activate);
    }
}
