<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;



/**
 * @OA\PathItem(
 *     path="/api/"
 * ),
 * @OA\Info(
 *     title="API Docs",
 *     version="1",
 * )
 *
 * @OA\Get (
 *     path="/api/v1/drivers",
 *     summary="Данные о водителях",
 *     tags={"Driver"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="10"),
 *                  @OA\Property(property="is_activate", type="integer", example="1"),
 *                  @OA\Property(property="name", type="string", example="Kayden Considine"),
 *                  @OA\Property(property="phone", type="string", example="79939677085"),
 *                  @OA\Property(property="location_activate", type="object",
 *                      @OA\Property(property="lat", type="float", example="55.751244"),
 *                      @OA\Property(property="lot", type="float", example="37.618423"),
 *                  ),
 *             ),
 *         )
 *     )
 * )
 * @OA\Get (
 *     path="/api/v1/drivers/{id}",
 *     summary="Данные о водителе",
 *     tags={"Driver"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="token", type="string", example="3|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *              )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object", ref="#/components/schemas/DriverResource"),
 *         )
 *     )
 * )
 *
 *
 * @OA\Schema(
 *      schema="UserDriverResource",
 *      @OA\Property(property="id", type="integer", example=18745988),
 *      @OA\Property(property="name", type="string", example="John Doe"),
 *      @OA\Property(property="phone", type="string", example="79955756252"),
 *      @OA\Property(property="role", type="string", example="driver"),
 *      @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *      @OA\Property(
 *          property="driver_data",
 *          type="object",
 *          @OA\Property(property="id", type="integer", example=3),
 *          @OA\Property(property="is_activate", type="boolean", example=1),
 *          @OA\Property(property="is_busy", type="boolean", example=false),
 *          @OA\Property(property="location_activate", type="object",
 *              @OA\Property(property="lat", type="float", example=55.751244),
 *              @OA\Property(property="lot", type="float", example=37.618423),
 *          ),
 *      )
 *  )
 *
 * @OA\Schema(
 *      schema="DriverResource",
 *      @OA\Property(property="id", type="integer", example=3),
 *      @OA\Property(property="is_activate", type="boolean", example=1),
 *      @OA\Property(property="is_busy", type="boolean", example=false),
 *      @OA\Property(property="name", type="string", example="John Doe"),
 *      @OA\Property(property="phone", type="string", example="79955756252"),
 *      @OA\Property(property="location_activate", type="object",
 *          @OA\Property(property="lat", type="float", example=55.751244),
 *          @OA\Property(property="lot", type="float", example=37.618423),
 *      ),
 *  )
 *
 * @OA\Schema(
 *      schema="UserResource",
 *      @OA\Property(property="id", type="integer", example=18745988),
 *      @OA\Property(property="name", type="string", example="John Doe"),
 *      @OA\Property(property="phone", type="string", example="79955756252"),
 *      @OA\Property(property="role", type="string", example="driver"),
 *      @OA\Property(property="email", type="string", example="johndoe@example.com"),
 * )
 *
 * @OA\Schema(
 *      schema="OrderActiveResource",
 *      @OA\Property(property="id", type="integer", example=18745988),
 *      @OA\Property(property="client", type="object",
 *          @OA\Property(property="id", type="integer", example=18745988),
 *          @OA\Property(property="name", type="string", example="John Doe"),
 *          @OA\Property(property="phone", type="string", example="79955756252"),
 *          @OA\Property(property="role", type="string", example="client"),
 *          @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *      ),
 *      @OA\Property(property="status", type="string", example="Активен"),
 *      @OA\Property(property="type", type="string", example="Регистрационные действия"),
 *      @OA\Property(property="location_start", type="object",
 *          @OA\Property(property="lat", type="float", example="55.751244"),
 *          @OA\Property(property="lot", type="float", example="37.618423"),
 *     ),
 *     @OA\Property(property="client_comment", type="string", example="Блабла бла"),
 *     @OA\Property(property="date_start", type="string|null", example="2024-09-28 21:30:46"),
 * )
 */
class MainController extends Controller
{

}
