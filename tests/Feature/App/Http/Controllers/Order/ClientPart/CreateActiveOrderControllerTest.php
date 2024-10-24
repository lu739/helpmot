<?php

namespace Tests\Feature\App\Http\Controllers\Order\ClientPart;

use App\Enum\OrderStatus;
use App\Enum\OrderType;
use App\Enum\UserRole;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateActiveOrderControllerTest extends TestCase
{
    use RefreshDatabase;
    private Collection $orders;
    private User $client;
    private array $newOrderData;
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = User::factory()->create([
            'role' => UserRole::CLIENT->value
        ]);

        Order::factory()->create([
            'client_id' => $this->client->id,
            'status' => OrderStatus::ACTIVE->value,
            'type' => OrderType::PURCHASE->value,
        ]);

        $this->newOrderData = [
            'client_id' => $this->client->id,
            'location_start' => json_decode(createLocation(), true),
            'comment' => 'test comment',
        ];

        $token = $this->client->createToken('test-token')->plainTextToken;

        Sanctum::actingAs($this->client, ['*']);
        $this->withHeader('Authorization', "Bearer $token");
    }

    public function test_return_error_if_has_active_order_this_type(): void
    {
        $data = array_merge($this->newOrderData, [
            'type' => OrderType::PURCHASE->value
        ]);

        $response = $this->post(route('client.orders.active.create', $data));

        $response
            ->assertStatus(403)
            ->assertJson(['message' => __('exceptions.client_already_has_active_order_this_type')]);
    }

    public function test_create_active_order_this_type(): void
    {
        $data = array_merge($this->newOrderData, [
            'type' => OrderType::TOW_TRUCK->value
        ]);

        $response = $this->post(route('client.orders.active.create', $data));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'client',
                    'location_start',
                    'status',
                    'type',
                    'date_start',
                    'client_comment',
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'client_id' => $this->client->id,
            'status' => OrderStatus::ACTIVE->value,
            'type' => OrderType::TOW_TRUCK->value,
            'client_comment' => $this->newOrderData['comment'],
        ]);
    }
}
