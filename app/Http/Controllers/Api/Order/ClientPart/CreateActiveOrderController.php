<?php

namespace App\Http\Controllers\Api\Order\ClientPart;

use App\Actions\Order\ClientPart\Create\CreateActiveOrderAction;
use App\Actions\Order\ClientPart\Create\Dto\CreateActiveOrderDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\ClientPart\CreateActiveOrderRequest;
use App\Http\Resources\Order\OrderActiveResource;

class CreateActiveOrderController extends Controller
{
    public function __invoke(CreateActiveOrderRequest $request)
    {
        $data = $request->validated();
        $dto = CreateActiveOrderDto::fromRequest($data);

        try {
            $order = (new CreateActiveOrderAction())->handle($dto);

            return response()->json([
                'data' => OrderActiveResource::make($order->load('client')),
            ]);
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
