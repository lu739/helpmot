<?php

namespace App\Http\Controllers\Swagger\Order\ClientPart;

use App\Http\Controllers\Controller;

/**
 *
 * @OA\Post (
 *     path="/api/v1/client/orders/active/create",
 *     summary="Содание активного заказа клиентом",
 *      description="
        Необходим токен авторизации (клиента).
        Возвращает новый созданный заказ в случае успеха.",
 *      tags={"Order"},
 *      security={{"sanctum": {}}},
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="type", type="string", example="tow_track"),
 *                  @OA\Property(property="location_start", type="object",
 *                       @OA\Property(property="lat", type="float", example="55.751244"),
 *                       @OA\Property(property="lot", type="float", example="37.618423"),
 *                   ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object", ref="#/components/schemas/OrderActiveResource"),
 *         )
 *     )
 * )
 *
 */
class CreateActiveOrderController extends Controller
{

}
