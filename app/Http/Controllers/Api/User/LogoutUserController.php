<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Post (
 *     path="/api/v1/logout",
 *     summary="Логаут юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *     )
 * )
 */
class LogoutUserController extends Controller
{
    public function __invoke(Request $request)
    {
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => __('auth.logout')
            ]);
        }
    }
}
