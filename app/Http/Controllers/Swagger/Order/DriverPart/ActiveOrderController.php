<?php

namespace App\Http\Controllers\Swagger\Order\DriverPart;

use App\Http\Controllers\Controller;

/**
 *
 * @OA\Get (
 *      path="/api/v1/driver/active/orders",
 *      summary="Данные об активных заказах для водителей",
 *      description="
            Необходим токен авторизации (водителя).
            Возвращает все активные заказы клиентов.",
 *      tags={"Order"},
 *      security={{"sanctum": {}}},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="11"),
 *                  @OA\Property(property="type", type="string", example="Эвакуатор"),
 *                  @OA\Property(property="location_start", type="object",
 *                       @OA\Property(property="lat", type="float", example="55.751244"),
 *                       @OA\Property(property="lot", type="float", example="37.618423"),
 *                       @OA\Property(property="address", type="string", example="19402 Langworth Crest\nSchneidermouth, WV 51672"),
 *                   ),
 *              ),
 *          )
 *      )
 *  )
 *
 * @OA\Get (
 *      path="/api/v1/driver/active/orders/{order}",
 *      summary="Данные об активном заказе для водителей",
 *      description="
            Необходим токен авторизации (водителя).
            Возвращает данные активного заказа клиента.",
 *      tags={"Order"},
 *      security={{"sanctum": {}}},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="11"),
 *                  @OA\Property(property="status", type="string", example="Создан"),
 *                  @OA\Property(property="type", type="string", example="Эвакуатор"),
 *                  @OA\Property(property="location_start", type="object",
 *                       @OA\Property(property="lat", type="float", example="55.751244"),
 *                       @OA\Property(property="lot", type="float", example="37.618423"),
 *                       @OA\Property(property="address", type="string", example="19402 Langworth Crest\nSchneidermouth, WV 51672"),
 *                   ),
 *                  @OA\Property(property="client", type="object",
 *                       @OA\Property(property="id", type="int", example=60081201),
 *                       @OA\Property(property="name", type="string", example="Marjorie Okuneva"),
 *                       @OA\Property(property="phone", type="string", example="79820354831"),
 *                       @OA\Property(property="role", type="string", example="client"),
 *                       @OA\Property(property="email", type="string", example="dahlia81@example.org"),
 *                   ),
 *                  @OA\Property(property="client_comment", type="string", example="some comments here"),
 *                  @OA\Property(property="date_start", type="string", example="2024-10-10 23:21:40"),
 *              ),
 *          )
 *      )
 *  )
 */
class ActiveOrderController extends Controller
{

}
