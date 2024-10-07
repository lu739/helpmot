<?php

namespace App\Http\Controllers\Api\User\Onboarding;

use App\Actions\OnboardingUser\Create\CreateOnboardingUserAction;
use App\Actions\OnboardingUser\Create\Dto\CreateOnboardingUserDto;
use App\Actions\OnboardingUser\Update\Dto\UpdateOnboardingUserDto;
use App\Actions\OnboardingUser\Update\UpdateOnboardingUserAction;
use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\OnboardingRequest;
use App\Http\Resources\OnboardingUser\OnboardingClientResource;
use App\Models\OnboardingUser;
use App\Services\ConfirmSms\ConfirmSmsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


/**
 * @OA\Post (
 *     path="/api/v1/onboarding",
 *     summary="Онбординг юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="phone", type="string", example="79161234567", description="Телефон юзера", nullable=false),
 *                  @OA\Property(property="password", type="string", example="1234567s", description="Пароль юзера", nullable=false),
 *                  @OA\Property(property="name", type="string", example="Some name", default="Some name", description="Имя юзера", nullable=true),
 *                  @OA\Property(property="role", type="string", example="driver", enum={"client", "driver"}, default="client", description="Роль юзера", nullable=true),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              @OA\Property(property="user", type="object",
 *                  @OA\Property(property="id", type="string", example="15735744919122398"),
 *                  @OA\Property(property="name", type="string", example="Some name"),
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *                  @OA\Property(property="role", type="string", example="client"),
 *              ),
 *         )
 *     )
 * )
 */
class OnboardingUserController extends Controller
{
    public function __construct(
        private readonly UpdateOnboardingUserAction $updateOnboardingUserAction,
        private readonly CreateOnboardingUserAction $createOnboardingUserAction,
        private readonly ConfirmSmsService          $confirmSmsService,
    )
    {
    }

    public function __invoke(OnboardingRequest $request)
    {
        $data = $request->validated();

        $onboardingUser = OnboardingUser::query()
            ->where('phone', $data['phone'])
            ->where('role', UserRole::from($data['role'])->value)
            ->first();

        try {
            DB::beginTransaction();

            if (!$onboardingUser || $onboardingUser->isCodeExpired()) {
                $phoneCode = random_int(100000, 999999);
                $phoneCodeDatetime = now()->format('Y-m-d H:i:s');
            }
            if (!$onboardingUser) {
                $createOnboardingUserDto = (new CreateOnboardingUserDto())
                    ->setName($data['name'] ?? 'User_' . Str::random(8))
                    ->setPhone($data['phone'])
                    ->setPhoneCode($phoneCode)
                    ->setPhoneCodeDatetime($phoneCodeDatetime)
                    ->setPassword(bcrypt($data['password']))
                    ->setRole(UserRole::CLIENT);
                $onboardingUser = $this->createOnboardingUserAction->handle($createOnboardingUserDto);
            } else {
                $updateOnboardingUserDto = (new UpdateOnboardingUserDto())
                    ->setId($onboardingUser->id)
                    ->setName($data['name'] ?? 'User_' . Str::random(8))
                    ->setPassword(bcrypt($data['password']))
                    ->setPhoneCode(isset($phoneCode) ? $phoneCode : null)
                    ->setPhoneCodeDatetime(isset($phoneCodeDatetime) ? $phoneCodeDatetime : null);

                $onboardingUser = $this->updateOnboardingUserAction->handle($updateOnboardingUserDto);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }

        if (app()->environment('testing')) {
            return response()->json([
                'user' => OnboardingClientResource::make($onboardingUser)->resolve(),
            ]);
        }

        if (app()->environment('local')) {
            return response()->json([
                'user' => OnboardingClientResource::make($onboardingUser)->resolve(),
            ]);
        } else {
            $response = $this->confirmSmsService->setSmsUser($onboardingUser)->sendSmsToUser();

            if ($response->status() === 200 && strtolower($response->json()['status']) === 'ok') {
                return response()->json([
                    'user' => OnboardingClientResource::make($onboardingUser)->resolve(),
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
