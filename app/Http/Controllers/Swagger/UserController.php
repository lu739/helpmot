<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Post (
 *     path="/api/v1/login",
 *     summary="Логин юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="email", type="string", example="test@test.com"),
 *                  @OA\Property(property="password", type="string", example="12345678"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="1|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *         )
 *     )
 * )
 */
class UserController extends Controller
{
}
