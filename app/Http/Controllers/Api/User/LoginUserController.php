<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Actions\User\GenerateTokens\GenerateTokensUserAction;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post (
 *     path="/api/v1/login",
 *     summary="Логин юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *                  @OA\Property(property="password", type="string", example="12345678"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object", ref="#/components/schemas/UserResource"),
 *             @OA\Property(property="access_token", type="string", example="1|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *             @OA\Property(property="refresh_token", type="string", example="2|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *         )
 *     )
 * )
 */
class LoginUserController extends Controller
{
    public function __construct(
        private readonly GenerateTokensUserAction $generateTokensUserAction,
    )
    {
    }

    public function __invoke(LoginRequest $request)
    {
        $data = $request->validated();

        $auth = Auth::guard('web')->attempt([
            'phone' => $data['phone'],
            'password' => $data['password'],
        ]);

        if (!$auth) {
            return responseFailed(401, __('exceptions.wrong_cridentials'));
        }

        $user = Auth::guard('web')->user();
        $tokens = $this->generateTokensUserAction->handle($user);

        return response()->json([
            'user' => UserResource::make($user)->resolve(),
            'accessToken' => $tokens['accessToken'],
            'accessTokenExpires' => $tokens['accessTokenExpires'],
            'refreshToken' => $tokens['refreshToken'],
        ]);
    }
}
