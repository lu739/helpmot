<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\DriverResource;
use App\Models\Driver;


/**
 * @OA\Post (
 *     path="/api/v1/drivers/deactivate",
 *     summary="Деактивация водителя (когда водитель завершает работу и не занят на заказе)",
 *     tags={"Driver"},
 *     description="
           Деактивация водителя. Необходим токен авторизации (водителя). Водитель не должен быть занят на действующем заказе",
 *     security={{"sanctum": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object", ref="#/components/schemas/DriverResource"),
 *         )
 *     )
 * )
 */

class DeactivateDriverController extends Controller
{
    public function __invoke()
    {
        $driver = Driver::findOrFail(request()->user()->driver->id);

        $driver->update([
            'is_activate' => 0,
            'location_activate' => null,
        ]);

        return response()->json([
            'data' => DriverResource::make($driver)->resolve(),
        ]);
    }
}
