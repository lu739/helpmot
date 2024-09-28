<?php

namespace Tests\Feature\Driver;

use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDriversTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        foreach (range(1, 5) as $userId) {
            Driver::factory()->create([
                'user_id' => $userId,
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
}
