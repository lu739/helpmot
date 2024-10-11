<?php

namespace App\Http\Controllers\Swagger\Order\DriverPart;

use App\Http\Controllers\Controller;

/**
 *
 * @OA\Get (
 *      path="/api/v1/driver/orders",
 *      summary="Данные о заказах водителя",
 *      description="
            Необходим токен авторизации.
            Возвращает все заказы водителя (которые он выполнял).",
 *      tags={"Order"},
 *      security={{"sanctum": {}}},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="11"),
 *                  @OA\Property(property="status", type="object",
 *                      @OA\Property(property="key", type="string", example="active"),
 *                      @OA\Property(property="value", type="string", example="Активный"),
 *                  ),
 *                  @OA\Property(property="type", type="object",
 *                      @OA\Property(property="key", type="string", example="tow_truck"),
 *                      @OA\Property(property="value", type="string", example="Эвакуатор"),
 *                  ),
 *             ),
 *          )
 *      )
 *  )
 *
 * @OA\Get (
 *      path="/api/v1/driver/orders/{order}",
 *      summary="Данные о заказе водителя",
 *      description="
            Необходим токен авторизации (водителя).
            Возвращает данные заказа водителя (который он выполнял).",
 *      tags={"Order"},
 *      security={{"sanctum": {}}},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="11"),
 *                  @OA\Property(property="driver_id", type="integer|null", example="87091160"),
 *                  @OA\Property(property="date_start", type="string", example="2024-09-28 21:30:46"),
 *                  @OA\Property(property="status", type="string", example="active"),
 *                  @OA\Property(property="type", type="string", example="tow_truck"),
 *                  @OA\Property(property="client_comment", type="string", example="Some comments here..."),
 *                  @OA\Property(property="location_start", type="object",
 *                      @OA\Property(property="lat", type="float", example="55.751244"),
 *                      @OA\Property(property="lot", type="float", example="37.618423"),
 *                      @OA\Property(property="address", type="string", example="19402 Langworth Crest\nSchneidermouth, WV 51672"),
 *                  ),
 *             ),
 *          )
 *      )
 *  )
 *
 * @OA\Post (
 *      path="/api/v1/driver/orders/{order}/take",
 *      summary="Взять заказ в работу (для водителей)",
 *      description="
            Необходим токен авторизации (водителя).
            Взять заказ в работу (для активированного и не занятого другим заказом водителя).",
 *      tags={"Order"},
 *      security={{"sanctum": {}}},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="11"),
 *                  @OA\Property(property="client", type="object",
 *                      @OA\Property(property="id", type="int", example=60081201),
 *                      @OA\Property(property="name", type="string", example="Marjorie Okuneva"),
 *                      @OA\Property(property="phone", type="string", example="79820354831"),
 *                      @OA\Property(property="role", type="string", example="client"),
 *                      @OA\Property(property="email", type="string", example="dahlia81@example.org"),
 *                  ),
 *                  @OA\Property(property="status", type="string", example="В процессе"),
 *                  @OA\Property(property="type", type="string", example="Эвакуатор"),
 *                  @OA\Property(property="client_comment", type="string", example="Some comments here..."),
 *                  @OA\Property(property="location_start", type="object",
 *                      @OA\Property(property="lat", type="float", example="55.751244"),
 *                      @OA\Property(property="lot", type="float", example="37.618423"),
 *                      @OA\Property(property="address", type="string", example="19402 Langworth Crest\nSchneidermouth, WV 51672"),
 *                  ),
 *                  @OA\Property(property="date_start", type="string", example="2024-10-10 23:21:40"),
 *             ),
 *          )
 *      )
 *  )
 */
class OrderController extends Controller
{

}
