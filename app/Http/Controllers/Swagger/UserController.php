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
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *                  @OA\Property(property="password", type="string", example="12345678"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string", example="1|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *             @OA\Property(property="refresh_token", type="string", example="2|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *         )
 *     )
 * )
 *
 * @OA\Post (
 *     path="/api/v1/register",
 *     summary="Регистрация юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *                  @OA\Property(property="password", type="string", example="12345678"),
 *                  @OA\Property(property="name", type="string", example="Some name"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              @OA\Property(property="access_token", type="string", example="3|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *              @OA\Property(property="refresh_token", type="string", example="4|OTdZhC1hXyYKSb8GasjSazFjak2RyN0G6rdDXQ4o2cffbd2f"),
 *         )
 *     )
 * )
 */
class UserController extends Controller
{
}
