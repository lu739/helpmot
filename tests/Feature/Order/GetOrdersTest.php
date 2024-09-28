<?php

namespace Tests\Feature\Order;

use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetOrdersTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $clients = User::query()
            ->where('role', UserRole::CLIENT->value)
            ->limit(5)->get();

        foreach ($clients as $client) {
            Order::factory()
                ->has(Driver::factory())
                ->count(20)
                ->create([
                    'client_id' => $client->id,
                ]);
        }

        $user = User::factory()->create([
            'role' => UserRole::CLIENT->value,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        Sanctum::actingAs($user, ['*']);
        $this->withHeader('Authorization', "Bearer $token");
    }

    /**
     * A basic feature test example.
     */
    public function test_get_orders(): void
    {
        $response = $this->get(route('orders.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'driver_id',
                    'date_start',
                    'status',
                    'type',
                    'location_start' => ['lat', 'lot', 'address'],
                    'client_comment',
                ]
            ]
        ]);
    }
}
