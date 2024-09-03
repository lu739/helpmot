<?php

namespace Tests\Feature\User\Onboarding;

use App\Enum\UserRole;
use App\Models\OnboardingUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Mockery;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    private string $phone = '79234567890';
    private string $password = 'securePassword123';

    public function test_create_and_send_sms(): void
    {
        $data = [
            'name' => 'Test User',
            'phone' => $this->phone,
            'password' => $this->password,
        ];

        $response = $this->post(route('onboarding'), $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['user'])
        ;

        $this->assertDatabaseHas('onboarding_users', [
            'phone' => $this->phone,
        ]);
    }

    public function test_existing_user()
    {
        $existingUser = OnboardingUser::create([
            'name' => 'Existing User',
            'phone' => $this->phone,
            'password' => $this->password,
            'phone_code' => '123456',
            'phone_code_datetime' => now()->format('Y-m-d H:i:s'),
            'role' => UserRole::CLIENT,
        ]);

        $data = [
            'name' => 'New User',
            'phone' => $this->phone,
            'password' => $this->password,
        ];

        $response = $this->post(route('onboarding'), $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['user']);

        $this->assertDatabaseHas('onboarding_users', [
            'phone' => $this->phone,
        ]);
    }
}
