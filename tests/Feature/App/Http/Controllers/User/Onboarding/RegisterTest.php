<?php

namespace Tests\Feature\App\Http\Controllers\User\Onboarding;

use App\Enum\UserRole;
use App\Models\OnboardingUser;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_successful_registration(): void
    {
        $onboardingUser = OnboardingUser::factory()->create([
            'phone' => '79161234567',
            'phone_code' => 123456,
            'phone_code_datetime' => now(),
            'role' => UserRole::CLIENT->value,
        ]);

        $requestData = [
            'phone' => '79161234567',
            'onboarding_id' => $onboardingUser->id,
            'phone_code' => 123456,
        ];

        $response = $this->post(route('register'), $requestData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'phone', 'name', 'role'],
                'accessToken',
                'accessTokenExpires',
                'refreshToken',
            ]);
    }

    public function testOnboardingUserNotFound()
    {
        $requestData = [
            'phone' => '79161234567',
            'onboarding_id' => 60980615,
            'phone_code' => 123456,
        ];

        $response = $this->post(route('register'), $requestData);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'onboarding_id' => [
                            0 => __('exceptions.onboarding_user_found_error'),
                        ]
                    ]
            ]);
    }
}
