<?php

namespace Tests\Feature\App\Http\Controllers\Order;

use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    private Collection $clients;
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->clients = User::factory(5)->create([
            'role' => UserRole::CLIENT->value
        ]);

        foreach ($this->clients as $client) {
            Order::factory()
                ->has(Driver::factory())
                ->count(20)
                ->create([
                    'client_id' => $client->id,
                ]);
        }

        $this->user = $this->clients->first();

        $token = $this->user->createToken('test-token')->plainTextToken;

        Sanctum::actingAs($this->user, ['*']);
        $this->withHeader('Authorization', "Bearer $token");
    }

    public function test_get_orders(): void
    {
        $response = $this->get(route('orders.index'));

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

    public function test_get_order(): void
    {
        $response = $this->get(route('orders.show', $this->user->orders()->first()));

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
