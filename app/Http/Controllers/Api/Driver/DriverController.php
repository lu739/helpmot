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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
           'data' => DriverResource::collection(Driver::all()->where('is_activate', 1)),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        $showDriver = Driver::find($driver->id);

        if (!$showDriver->isActive()) {
            throw new DriverNotActiveException();
        }

        return response()->json([
            'data' => DriverResource::make($showDriver)->resolve(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
