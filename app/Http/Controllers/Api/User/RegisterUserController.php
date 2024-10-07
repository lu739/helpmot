<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\OnboardingUser;
use App\Actions\OnboardingUser\Check\CheckOnboardingUserAction;
use App\Actions\OnboardingUser\Check\Dto\CheckOnboardingUserDto;
use App\Actions\User\Create\CreateUserAction;
use App\Actions\User\Create\Dto\CreateUserDto;
use App\Actions\User\GenerateTokens\GenerateTokensUserAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Post (
 *     path="/api/v1/register",
 *     summary="Регистрация юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *                  @OA\Property(property="onboarding_id", type="integer", example=23989484565138245),
 *                  @OA\Property(property="phone_code", type="integer", example=123456),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              @OA\Property(property="access_token", type="string", example="3|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *              @OA\Property(property="refresh_token", type="string", example="4|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *         )
 *     )
 * )
 */
class RegisterUserController extends Controller
{
    public function __construct(
        private readonly CreateUserAction         $createUserAction,
        private readonly GenerateTokensUserAction $generateTokensUserAction,
    )
    {
    }

    public function __invoke(RegisterRequest $request)
    {
        $dto = CheckOnboardingUserDto::fromRequest($request);
        $dto->setCaseType('register');

        $checkedOnboardingUser = (new CheckOnboardingUserAction())
            ->handle($dto);

        if (!$checkedOnboardingUser instanceof OnboardingUser) {
            return $checkedOnboardingUser;
        }

        try {
            DB::beginTransaction();

                $createUserDto = (new CreateUserDto())
                    ->setPhone($dto->getPhone())
                    ->setName($checkedOnboardingUser['name'])
                    ->setPassword($checkedOnboardingUser['password'])
                    ->setPhoneVerified(now()->format('Y-m-d H:i:s'))
                    ->setRole(UserRole::from($checkedOnboardingUser['role']));

                $user = $this->createUserAction->handle($createUserDto);

                $checkedOnboardingUser->user()->associate($user)->save();

            DB::commit();

            Auth::guard()->login($user);

            $tokens = $this->generateTokensUserAction->handle($user);

            return response()->json([
                'user' => UserResource::make($user)->resolve(),
                'accessToken' => $tokens['accessToken'],
                'accessTokenExpires' => $tokens['accessTokenExpires'],
                'refreshToken' => $tokens['refreshToken'],
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
