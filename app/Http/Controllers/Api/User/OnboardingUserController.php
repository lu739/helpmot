<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\OnboardingRequest;
use App\Http\Resources\OnboardingUser\OnboardingClientResource;
use App\UseCases\OnboardingUser\Create\CreateOnboardingUserUseCase;
use App\UseCases\OnboardingUser\Create\Dto\CreateOnboardingUserDto;
use Illuminate\Support\Facades\Http;
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
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *                  @OA\Property(property="password", type="string", example="12345678"),
 *                  @OA\Property(property="name", type="string", example="Some name"),
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
    )
    {
    }

    public function __invoke(OnboardingRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $createOnboardingUserDto = (new CreateOnboardingUserDto())
                ->setName($data['name'] ?? 'User_' . Str::random(8))
                ->setPhone($data['phone'])
                ->setPhoneСode(random_int(100000, 999999))
                ->setPhoneСodeDatetime(now()->format('Y-m-d H:i:s'))
                ->setPassword(bcrypt($data['password']))
                ->setRole(UserRole::CLIENT);
            $user = $this->createOnboardingUserUseCase->handle($createOnboardingUserDto);

            DB::commit();
// TODO если такой телефон уже есть в онбординге, то следующий код отправлять через n минут
            $data = [
                'messages' => [
                    [
                        'phone' => $createOnboardingUserDto->getPhone(),
                        'sender' => 'SMS DUCKOHT',
                        'clientId' => $user->id,
                        'text' => 'Код подтверждения ' . $createOnboardingUserDto->getPhoneСode() . '. Ваш "HelpMot"',
                    ],
                ],
                'login' => env('SMS_LOGIN'),
                'password' => env('SMS_PASSWORD'),
            ];

            $response = Http::post(env('SMS_ADDRESS'), $data);

            if ($response->status() === 200) {
                return response()->json([
                    'user' => OnboardingClientResource::make($user)->resolve(),
                ]);
            } else {
                throw new \Exception($response->body());
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
