<?php

namespace Tests\Feature\User\Onboarding;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CreateAndSendSms extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_create_and_send_sms(): void
    {
        Http::fake([
            'sms-service-url' => Http::response(['status' => 'ok'], 200),
        ]);

        $data = [
            'name' => 'Test User',
            'phone' => '1234567890',
            'password' => 'securePassword123',
        ];

        $response = $this->postJson(route('onboarding'), $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['user']);

        $this->assertDatabaseHas('onboarding_users', [
            'phone' => '1234567890',
        ]);
    }
}
