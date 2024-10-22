<?php

namespace App\Http\Controllers\Swagger\Order\DriverPart;

use App\Http\Controllers\Controller;

/**
 *
 * @OA\Post (
 *      path="/api/v1/driver/orders/{order}/update-location",
 *      summary="Изменение локации водителя",
 *      description="
            Необходим токен авторизации (водителя).
 *     ",
 *      tags={"Order"},
 *      security={{"sanctum": {}}},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                  @OA\Property(property="datetime", type="datetime", example="2024-10-23 01:19:00"),
 *                  @OA\Property(property="last_location", type="object",
 *                       @OA\Property(property="lat", type="float", example="55.751244"),
 *                       @OA\Property(property="lot", type="float", example="37.618423"),
 *                  ),
 *             ),
 *          )
 *      )
 *  )
 */

class UpdateLocationByDriverOrderController extends Controller
{
}
