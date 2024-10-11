<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\UserDriverResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;


/**
 * @OA\Get(
 *     path="/api/v1/data_user",
 *     summary="Получить данные юзера",
 *     description="
        Необходим токен авторизации.
        Возвращает информацию о пользователе, если он авторизован. Если авторизован водитель - приходит расширенная информация с driver_data.
       ",
 *     tags={"User"},
 *     security={{"sanctum": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Информация о пользователе",
 *     @OA\JsonContent(
 *             oneOf={
 *                 @OA\Schema(ref="#/components/schemas/UserDriverResource"),
 *                 @OA\Schema(ref="#/components/schemas/UserResource"),
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Пользователь не найден",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="exceptions.user_not_found")
 *         )
 *     )
 * )
 *
 */

class DataUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return responseFailed(404, __('exceptions.user_not_found'));
        }

        if ($user->role === UserRole::DRIVER->value) {
            return response()->json([
                'user' => UserDriverResource::make($user->load('driver'))->resolve(),
            ], 200);
        }

        return response()->json([
            'user' => UserResource::make($user)->resolve(),
        ], 200);
    }
}
