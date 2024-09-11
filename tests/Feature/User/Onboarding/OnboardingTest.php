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
    private string $wrongFormatPhone = '912345689';
    private string $wrongFormatPassword = 'password';


    public function test_validation_wrong_formats(): void
    {
        $data = [
            'name' => 'Test User',
            'phone' => $this->wrongFormatPhone,
            'password' => $this->wrongFormatPassword,
        ];

        $response = $this->post(route('onboarding'), $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'phone',
                    'password',
                ]
            ])
        ;
    }

    public function test_validation_required_fields(): void
    {
        $data = [
            'name' => 'Test User',
            'phone' => '',
            'password' => '',
        ];
        $response = $this->post(route('onboarding'), $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'phone',
                    'password',
                ]
            ])
        ;
    }

    public function test_create_new_onboarding_user(): void
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

    public function test_existing_onboarding_user()
    {
        OnboardingUser::create([
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
            'name' => $data['name'],
        ]);
    }
}
