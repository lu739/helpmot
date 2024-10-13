<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Driver\ActivateRequest;
use App\Http\Resources\Driver\DriverResource;
use App\Models\Driver;


/**
 * @OA\Post (
 *     path="/api/v1/drivers/{driver}/activate",
 *     summary="Активация водителя (когда водитель готов работать)",
 *     tags={"Driver"},
 *     description="
           Активация водителя. Необходим токен авторизации (водителя).",
 *     security={{"sanctum": {}}},
 *     @OA\RequestBody(
 *          @OA\MediaType(
 *               mediaType="application/json",
 *               @OA\Schema(
 *                   @OA\Property(property="location_activate", type="array",
 *                       @OA\Items(
 *                           @OA\Property(property="lot", type="number", example=56.04298),
 *                           @OA\Property(property="lat", type="number", example=37.41788),
 *                       ),
 *                   ),
 *               )
 *          )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object", ref="#/components/schemas/DriverResource"),
 *         )
 *     )
 * )
 */
class ActivateDriverController extends Controller
{
    public function __invoke(ActivateRequest $request, Driver $driver)
    {
        $driver->update([
            'is_activate' => 1,
            'location_activate' => json_encode($request->input('location_activate')),
        ]);

        return response()->json([
            'data' => DriverResource::make($driver)->resolve(),
        ]);
    }
}
