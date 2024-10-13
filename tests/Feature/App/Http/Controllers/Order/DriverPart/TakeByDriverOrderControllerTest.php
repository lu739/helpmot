<?php

namespace Tests\Feature\App\Http\Controllers\Order\DriverPart;

use App\Enum\OrderStatus;
use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TakeByDriverOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $driverUser;
    private User $client;
    private Driver $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->driverUser = User::factory()->create([
            'role' => UserRole::DRIVER->value
        ]);

        $this->driver = Driver::factory()
            ->create([
                'user_id' => $this->driverUser->id,
                'is_busy' => false,
                'is_activate' => true,
            ]);

        $this->client = User::factory()->create([
            'role' => UserRole::CLIENT->value
        ]);

        $this->order = Order::factory()->create([
            'driver_id' => $this->driverUser->id,
            'client_id' => $this->client->id,
            'status' => OrderStatus::ACTIVE->value
        ]);

        $token = $this->driverUser->createToken('test-token')->plainTextToken;

        Sanctum::actingAs($this->driverUser, ['*']);
        $this->withHeader('Authorization', "Bearer $token");
    }


    public function test_driver_can_take_order(): void
    {
        $response = $this->post(route('driver.orders.take', $this->order->id));

        $this->order->refresh();
        $this->driver->refresh();

        $response->assertOk();
        $this->assertTrue($this->order->status === OrderStatus::IN_PROGRESS->value);
        $this->assertEquals($this->order->driver_id, $this->driver->user->id);
        $this->assertTrue($this->driver->is_busy);
        $this->assertNotNull($this->order->date_start);
    }
}
