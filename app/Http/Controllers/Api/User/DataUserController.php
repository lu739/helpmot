<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\OnboardingUser;
use App\UseCases\User\Create\CreateUserUseCase;
use App\UseCases\User\Create\Dto\CreateUserDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Get (
 *     path="/api/v1/data-user",
 *     summary="Данные юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="token", type="string", example="3|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object",
 *                  @OA\Property(property="id", type="string", example="15735744919122398"),
 *                  @OA\Property(property="name", type="string", example="Some name"),
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *                  @OA\Property(property="role", type="string", example="client"),
 *                  @OA\Property(property="email", type="string", example="name@yandex.ru"),
 *             ),
 *         )
 *     )
 * )
 */
class DataUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return responseFailed(404, __('exceptions.user_not_found'));
        }

        return response()->json([
            'user' => UserResource::make($user)->resolve(),
        ], 500);
    }
}
