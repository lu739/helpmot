<?php

namespace App\Http\Controllers\Swagger\Order\DriverPart;

use App\Http\Controllers\Controller;

/**
 *
 * @OA\Post (
 *      path="/api/v1/driver/orders/{order}/cancel",
 *      summary="Отмена заказа водителем",
 *      description="
            Необходим токен авторизации (водителя).
            Возвращает отмененный заказ.",
 *      tags={"Order"},
 *      security={{"sanctum": {}}},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="driver_id", type="integer", example="null"),
 *                  @OA\Property(property="client_id", type="integer", example="17938572"),
 *                  @OA\Property(property="status", type="string", example="Отмена водителем"),
 *                  @OA\Property(property="date_start", type="string", example="2022-01-01T00:00:00.000000Z"),
 *                  @OA\Property(property="date_end", type="string", example="2022-01-01T00:00:00.000000Z"),
 *                  @OA\Property(property="type", type="string", example="Эвакуатор"),
 *              ),
 *          )
 *      )
 *  )
 */
class CancelledByDriverOrderController extends Controller
{

}