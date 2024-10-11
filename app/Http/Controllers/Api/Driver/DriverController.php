<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\DriverResource;
use App\Models\Driver;
use App\Services\Exceptions\Driver\DriverNotActiveException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DriverController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: [
                'index',
                // 'show'
            ]),
        ];
    }

    public function index()
    {
        return response()->json([
           'data' => DriverResource::collection(Driver::query()->with('user')->where('is_activate', 1)
               ->get()),
        ]);
    }

    public function store(Request $request)
    {
        //
    }


    public function show(Driver $driver)
    {
        if (!$driver->isActivate()) {
            throw new DriverNotActiveException();
        }

        return response()->json([
            'data' => DriverResource::make($driver)->resolve(),
        ]);
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
