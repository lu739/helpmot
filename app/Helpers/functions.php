<?php

function responseOk($status = 200, $return = []): \Illuminate\Http\JsonResponse
{
    $returnedArray = array_merge([
        'message' => 'success'
    ], $return);

    return response()->json($returnedArray, $status);
}
function responseFailed($status = 400, $message = 'failed'): \Illuminate\Http\JsonResponse
{
    return response()->json([
        'message' => $message
    ], $status);
}
