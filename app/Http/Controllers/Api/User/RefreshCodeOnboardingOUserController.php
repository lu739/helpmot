<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\RefreshCodeOnboardingUserRequest;
use App\Http\Resources\User\UserMinifiedResource;
use App\Models\OnboardingUser;
use App\Services\ConfirmSms\ConfirmSmsService;
use App\UseCases\OnboardingUser\RefreshPhoneCode\Dto\RefreshPhoneCodeOnboardingUserDto;
use App\UseCases\OnboardingUser\RefreshPhoneCode\RefreshPhoneCodeOnboardingUserUseCase;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Post (
 *     path="/api/v1/refresh-onboarding-user-code",
 *     summary="Смена смс-кода юзера при онбординге",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="phone", type="string", example="79161234567", description="Телефон онбординг-юзера", nullable=false),
 *                  @OA\Property(property="role", type="string", example="driver", enum={"client", "driver"}, description="Роль онбординг-юзера, которому нужна смена пароля", nullable=false),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              @OA\Property(property="user", type="object",
 *                  @OA\Property(property="id", type="string", example="15735744919122398"),
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *                  @OA\Property(property="role", type="string", example="driver"),
 *                  @OA\Property(property="phone_code_expired_datetime", type="string", example="2024-09-06 20:31:27"),
 *              ),
 *         )
 *     )
 * )
 */
class RefreshCodeOnboardingOUserController extends Controller
{
    public function __construct(
        private readonly RefreshPhoneCodeOnboardingUserUseCase $refreshPhoneCodeOnboardingUserUseCase,
        private readonly ConfirmSmsService $confirmSmsService,
    )
    {
    }

    public function __invoke(RefreshCodeOnboardingUserRequest $request)
    {
        $data = $request->validated();

        $onboardingUser = OnboardingUser::query()
            ->where('phone', $data['phone'])
            ->where('role', $data['role'])
            ->first();

        if (!$onboardingUser) {
            return responseFailed(404, __('exceptions.user_not_found'));
        }

        if ($onboardingUser->user()) {
            return responseFailed(403, __('exceptions.user_already_exists'));
        }

        if (isset($onboardingUser->phone_code) && isset($onboardingUser->phone_code_datetime) && !$onboardingUser->isCodeExpired()) {
            return responseFailed(403, __('exceptions.user_code_not_expired'));
        }

        try {
            DB::beginTransaction();

            $refreshPhoneCodeOnboardingUserDto = (new RefreshPhoneCodeOnboardingUserDto())
                ->setPhoneCode(random_int(100000, 999999))
                ->setPhoneCodeDatetime(now()->format('Y-m-d H:i:s'))
                ->setId($onboardingUser->id);
            $onboardingUser = $this->refreshPhoneCodeOnboardingUserUseCase->handle($refreshPhoneCodeOnboardingUserDto);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return responseFailed(500, $exception->getMessage());
        }

        if (app()->environment('local')) {
            return response()->json([
                'user' => UserMinifiedResource::make($onboardingUser)->resolve(),
            ]);
        } else {
            $response = $this->confirmSmsService->setSmsUser($onboardingUser)->sendSmsToUser();

            if ($response->status() === 200 && strtolower($response->json()['status']) === 'ok') {
                return response()->json([
                    'user' => UserMinifiedResource::make($onboardingUser)->resolve(),
                ]);
            } else {
                $message = __('exceptions.sms_server_error') .
                    ': ' . ($response->json()['description'] ?? 'Unknown error') .
                    ' (status: ' . ($response->json()['status'] ?? 'Unknown status') . ')';

                return response()->json([
                    'message' => $message,
                ], 500);
            }
        }
    }
}
