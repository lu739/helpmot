<?php

if (!function_exists('responseOk')) {
    function responseOk($status = 200, $return = []): \Illuminate\Http\JsonResponse
    {
        $returnedArray = array_merge([
            'message' => 'success'
        ], $return);

        return response()->json($returnedArray, $status);
    }
}

if (!function_exists('responseFailed')) {
    function responseFailed($status = 400, $message = 'failed'): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $status);
    }
}


if (!function_exists('createLocation')) {
    function createLocation(?string $address = null): string
    {
        $location = [
            'lot' => round(mt_rand(config()->get('location.lot.min') * 100000, config()->get('location.lot.max') * 100000) / 100000, 5),
            'lat' => round(mt_rand(config()->get('location.lat.min') * 100000, config()->get('location.lat.max') * 100000) / 100000, 5),
        ];

        if ($address) {
            $location['address'] = $address;
        }

        return json_encode($location);
    }
}


