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
 *
 */
class MainController extends Controller
{

}
