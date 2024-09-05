<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\OnboardingUser;
use App\UseCases\User\Create\CreateUserUseCase;
use App\UseCases\User\Create\Dto\CreateUserDto;
use App\UseCases\User\GenerateTokens\GenerateTokensUserUseCase;
use Carbon\Carbon;
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
        private readonly CreateUserUseCase $createUserUseCase,
        private readonly GenerateTokensUserUseCase $generateTokensUserUseCase,
    )
    {
    }

    public function __invoke(RegisterRequest $request)
    {
        $data = $request->validated();

        $onboardingUser = OnboardingUser::query()
            ->where('phone', $data['phone'])
            ->where('id', $data['onboarding_id'])
            ->first();

        if (!$onboardingUser) {
            return response()->json([
                'message' => __('exceptions.onboarding_user_found_error')
            ], 404);
        }
        if ($onboardingUser['phone_code'] != $data['phone_code']) {
            return response()->json([
                'message' => __('exceptions.phone_code_error')
            ], 404);
        }

        if ($onboardingUser->isCodeExpired()) {
            return response()->json([
                'message' => __('exceptions.phone_code_expired_error')
            ], 400);
        }

        try {
            DB::beginTransaction();
            $createUserDto = (new CreateUserDto())
                ->setPhone($data['phone'])
                ->setName($onboardingUser['name'])
                ->setPassword($onboardingUser['password'])
                ->setPhoneVerified(now()->format('Y-m-d H:i:s'))
                ->setRole(UserRole::from($onboardingUser['role']));
            $user = $this->createUserUseCase->handle($createUserDto);

            $onboardingUser->user()->associate($user)->save();

            DB::commit();

            Auth::guard()->login($user);

            $tokens = $this->generateTokensUserUseCase->handle($user);

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
