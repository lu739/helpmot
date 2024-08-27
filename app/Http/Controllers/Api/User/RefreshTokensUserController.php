<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\UseCases\User\GenerateTokens\GenerateTokensUserUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post (
 *     path="/api/v1/refresh-token",
 *     summary="Обновление токенов юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="refresh_token", type="string", example="5|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string", example="7|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *             @OA\Property(property="refresh_token", type="string", example="8|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *         )
 *     )
 * )
 */
class RefreshTokensUserController extends Controller
{
    public function __construct(
        private readonly GenerateTokensUserUseCase $generateTokensUserUseCase,
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();
        $request->user()->tokens()->delete();
        $tokens = $this->generateTokensUserUseCase->handle($user);

        return response()->json([
            'user' => UserResource::make($user)->resolve(),
            'accessToken' => $tokens['accessToken'],
            'accessTokenExpires' => $tokens['accessTokenExpires'],
            'refreshToken' => $tokens['refreshToken'],
        ]);
    }
}
