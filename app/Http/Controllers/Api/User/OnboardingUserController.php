<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\OnboardingRequest;
use App\Http\Resources\OnboardingUser\OnboardingClientResource;
use App\Models\OnboardingUser;
use App\Services\ConfirmSms\ConfirmSmsService;
use App\UseCases\OnboardingUser\Create\CreateOnboardingUserUseCase;
use App\UseCases\OnboardingUser\Create\Dto\CreateOnboardingUserDto;
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
        private readonly CreateOnboardingUserUseCase $createOnboardingUserUseCase,
        private readonly ConfirmSmsService $confirmSmsService,
    )
    {
    }

    public function __invoke(OnboardingRequest $request)
    {
        $data = $request->validated();

        $onboardingUser = OnboardingUser::query()
            ->where('phone', $data['phone'])
            ->first();

        if (!$onboardingUser) {
            try {
                DB::beginTransaction();

                $createOnboardingUserDto = (new CreateOnboardingUserDto())
                    ->setName($data['name'] ?? 'User_' . Str::random(8))
                    ->setPhone($data['phone'])
                    ->setPhoneСode(random_int(100000, 999999))
                    ->setPhoneСodeDatetime(now()->format('Y-m-d H:i:s'))
                    ->setPassword(bcrypt($data['password']))
                    ->setRole(UserRole::CLIENT);
                $onboardingUser = $this->createOnboardingUserUseCase->handle($createOnboardingUserDto);

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();

                return response()->json([
                    'message' => $exception->getMessage()
                ], 500);
            }
        }

        $response = $this->confirmSmsService->setOnboardingUser($onboardingUser)->sendSmsToOnboardingUser();

        if ($response->status() === 200 && $response->json()['status'] === 'OK') {
            return response()->json([
                'user' => OnboardingClientResource::make($onboardingUser)->resolve(),
            ]);
        } else {
            return response()->json([
                'message' => $response->json()['description'], // ToDO тут может лучше сделать сообщение, что нет связи с сервисом СМС?
            ], 500);
        }
    }
}
