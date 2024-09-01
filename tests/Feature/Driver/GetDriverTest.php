<?php

namespace Tests\Feature\Driver;

use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDriverTest extends TestCase
{
    private Driver $driver;
    protected function setUp(): void
    {
        parent::setUp();

        $this->driver = Driver::factory()
            ->for(User::factory())
            ->has(Order::factory(2))
            ->create(['is_activate' => true]);
    }
    /**
     * A basic feature test example.
     */
    public function test_get_product(): void
    {
        $response = $this->get(route('driver.show', $this->driver));

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'is_activate',
            'location_activate' => ['lat', 'lot'],
            'name',
            'phone',
        ]);
        $response->assertJson([
            'id' => $this->driver->id,
            'name' => $this->driver->name,
            'is_activate' => $this->driver->is_activate,
            'phone' => $this->driver->phone,
        ]);
    }
}
