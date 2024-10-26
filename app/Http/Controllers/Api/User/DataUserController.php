<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\UserDriverResource;
use App\Http\Resources\Order\OrderInProgressResource;
use App\Http\Resources\User\UserResource;
use App\Models\Order;
use App\Services\SaveLocation\Interfaces\SaverLocationInterface;
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
 *                  @OA\Property(property="data", type="object",
 *                      @OA\Property(property="user", type="object", ref="#/components/schemas/UserDriverResource"),
 *                      @OA\Property(property="current_order", type="object|null", ref="#/components/schemas/OrderInProgressResource"),
 *                  ),
 *                  @OA\Property(property="data", type="object",
 *                      @OA\Property(property="user", type="object", ref="#/components/schemas/UserResource"),
 *                      @OA\Property(property="current_order", type="object|null", ref="#/components/schemas/OrderInProgressResource"),
 *                  ),
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Пользователь не найден",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User not found")
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
            $currentOrder = $user->driver->inProgressOrder;
            $this->updateOrderIfHasSaverLocation($currentOrder);

            return response()->json([
                'user' => UserDriverResource::make($user->load('driver'))->resolve(),
                'current_order' => $currentOrder ?
                    OrderInProgressResource::make($currentOrder)->resolve() :
                    null,
            ], 200);
        }

        $currentOrder = $user->inProgressOrder;
        $this->updateOrderIfHasSaverLocation($currentOrder);

        return response()->json([
            'user' => UserResource::make($user)->resolve(),
            'current_order' => $currentOrder ?
                OrderInProgressResource::make($currentOrder)->resolve() :
                null,
        ], 200);
    }

    private function updateOrderIfHasSaverLocation(?Order $order): void
    {
        if ($order && $order->exists()) {
            $saver = app(SaverLocationInterface::class);

            $lastLocationItem = $saver->getLastItem($order->id);
            $order->load('driver', 'client');

            if ($lastLocationItem) {
                $order->order_location = json_encode($lastLocationItem);
            } else {
                $order->load('orderLocation');
            }
        }
    }
}
