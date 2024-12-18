<?php

namespace App\Http\Controllers\Swagger\Order\ClientPart;

use App\Http\Controllers\Controller;

/**
 *
 * @OA\Get (
 *     path="/api/v1/orders",
 *     summary="Данные о заказах юзера",
 *     tags={"Order"},
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
 *             @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="11"),
 *                  @OA\Property(property="driver_id", type="integer|null", example="87091160"),
 *                  @OA\Property(property="status", type="object",
 *                      @OA\Property(property="key", type="string", example="active"),
 *                      @OA\Property(property="value", type="string", example="Активный"),
 *                  ),
 *                  @OA\Property(property="type", type="object",
 *                      @OA\Property(property="key", type="string", example="tow_truck"),
 *                      @OA\Property(property="value", type="string", example="Эвакуатор"),
 *                  ),
 *             ),
 *         )
 *     )
 * )
 *
 * @OA\Get (
 *     path="/api/v1/orders/{id}",
 *     summary="Данные о заказе юзера",
 *     tags={"Order"},
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
 *         )
 *     )
 * )
 *
 * @OA\Get (
 *     path="/api/v1/data-order-enums",
 *     summary="Данные статусов/типов по заказам для селектов",
 *     tags={"Order"},
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
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="types", type="object",
 *                       @OA\Property(property="tow_truck", type="string", example="Эвакуатор"),
 *                 ),
 *                 @OA\Property(property="statuses", type="object",
 *                       @OA\Property(property="active", type="string", example="Активен"),
 *                 ),
 *             ),
 *         )
 *     )
 * )
 *
 */
class HistoryOrderController extends Controller
{

}
