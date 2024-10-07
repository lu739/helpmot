<?php

namespace Tests\Feature\App\Http\Controllers\User\Onboarding;

use App\Actions\OnboardingUser\Check\CheckOnboardingUserAction;
use App\Actions\OnboardingUser\Check\Dto\CheckOnboardingUserDto;
use App\Enum\UserRole;
use App\Models\OnboardingUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class RefreshTokensUserControllerTest extends TestCase
{
    use RefreshDatabase;
    private string $phone = '79234567890';
    private string $password = 'securePassword123';
    private string $role = UserRole::CLIENT->value;


    public function test_refresh_user_code_onboarding_user()
    {
        OnboardingUser::factory()
            ->create([
                'name' => 'Onboarding User',
                'phone' => $this->phone,
                'password' => $this->password,
                'phone_code' => '123456',
                'phone_code_datetime' => now()->format('Y-m-d H:i:s'),
                'role' => $this->role,
            ]);

        $data = [
            'phone' => $this->phone,
            'role' => $this->role,
        ];

        $response = $this->post(route('refresh_onboarding_user_code'), $data);

        $response->assertOk()
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'phone',
                    'phone_code_expired_datetime',
                    'role'
                ]
            ]);
    }

    public function test_action_returns_non_onboarding_user()
    {
        // Создаем мок для DTO
        $dtoMock = Mockery::mock(CheckOnboardingUserDto::class);

        $failedResponse = responseFailed(404, 'Some other result');
        // Создаем мок для действия
        $actionMock = Mockery::mock(CheckOnboardingUserAction::class);
        $actionMock->shouldReceive('handle')
            ->with($dtoMock)
            ->andReturn($failedResponse);

        // Проверяем, что handle возвращает не OnboardingUser
        $result = $actionMock->handle($dtoMock);
        $this->assertNotInstanceOf(OnboardingUser::class, $result);
        $this->assertEquals($failedResponse, $result);
    }

}
