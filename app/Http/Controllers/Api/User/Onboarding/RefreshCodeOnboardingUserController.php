<?php

namespace App\Http\Controllers\Api\User\Onboarding;

use App\Actions\OnboardingUser\Check\CheckOnboardingUserAction;
use App\Actions\OnboardingUser\Check\Dto\CheckOnboardingUserDto;
use App\Actions\User\RefreshPhoneCode\Dto\RefreshPhoneCodeUserDto;
use App\Actions\User\RefreshPhoneCode\RefreshPhoneCodeUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\RefreshCodeOnboardingUserRequest;
use App\Http\Resources\User\UserMinifiedResource;
use App\Models\OnboardingUser;
use App\Services\ConfirmSms\ConfirmSmsService;
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
class RefreshCodeOnboardingUserController extends Controller
{
    public function __construct(
        private readonly RefreshPhoneCodeUserAction $refreshPhoneCodeOnboardingUserAction,
        private readonly ConfirmSmsService          $confirmSmsService,
    )
    {
    }

    public function __invoke(RefreshCodeOnboardingUserRequest $request)
    {
        $dto = CheckOnboardingUserDto::fromRequest($request);
        $dto->setCaseType('refresh_code');

        $checkedOnboardingUser = (new CheckOnboardingUserAction())
            ->handle($dto);

        if (!$checkedOnboardingUser instanceof OnboardingUser) {
            return $checkedOnboardingUser;
        }

        try {
            DB::beginTransaction();

            $refreshPhoneCodeOnboardingUserDto = (new RefreshPhoneCodeUserDto())
                ->setModel(new OnboardingUser())
                ->setPhoneCode(random_int(100000, 999999))
                ->setPhoneCodeDatetime(now()->format('Y-m-d H:i:s'))
                ->setId($checkedOnboardingUser->id);
            $onboardingUser = $this->refreshPhoneCodeOnboardingUserAction
                ->handle($refreshPhoneCodeOnboardingUserDto);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return responseFailed(500, $exception->getMessage());
        }

        if (!app()->environment('production')) {
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
