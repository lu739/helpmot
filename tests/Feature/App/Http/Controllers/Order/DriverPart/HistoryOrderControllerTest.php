<?php

namespace Tests\Feature\App\Http\Controllers\Order\DriverPart;

use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HistoryOrderControllerTest extends TestCase
{
    use RefreshDatabase;
    private Collection $driverUsers;
    private User $client;
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->driverUsers = User::factory(5)->create([
            'role' => UserRole::DRIVER->value
        ]);
        foreach ($this->driverUsers as $driverUser) {
            Driver::factory()
                ->create(['user_id' => $driverUser->id]);
        }

        $this->client = User::factory()->create([
            'role' => UserRole::CLIENT->value
        ]);

        foreach ($this->driverUsers as $driverUser) {
            Order::factory()
                ->create([
                    'driver_id' => $driverUser->id,
                    'client_id' => $this->client->id]
                );
        }

        $this->user = $this->driverUsers->first();

        $token = $this->user->createToken('test-token')->plainTextToken;

        Sanctum::actingAs($this->user, ['*']);
        $this->withHeader('Authorization', "Bearer $token");
    }

    public function test_get_driver_orders(): void
    {
        $response = $this->get(route('driver.orders.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'type',
                ]
            ]
        ]);
    }

    public function test_get_driver_order(): void
    {
        $response = $this->get(route('driver.orders.show', $this->user->driver->orders->first()));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'driver_id',
                'date_start',
                'status',
                'type',
                'location_start' => ['lat', 'lot', 'address'],
                'client_comment',
            ]
        ]);
    }
}
