<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\DriverPart\UpdateLocationRequest;
use App\Jobs\SaveLastOrderLocationJob;
use App\Models\Order;
use App\Models\OrderLocation;
use App\Services\SaveLocation\Interfaces\SaverLocationInterface;
use Carbon\Carbon;

class UpdateLocationByDriverOrderController extends Controller
{
    public function __invoke(
        Order $order,
        UpdateLocationRequest $request,
        SaverLocationInterface $saver
    )
    {
        $data = $request->validated();

        $data['order_id'] = $order->id;
        $data['driver_id'] = $order->driver_id;
        $data['datetime'] = Carbon::createFromFormat('Y-m-d H:i:s', $data['datetime']);

        try {
            $count = $saver->saveItem($data);

            if ($count > env('REDIS_MAX_ORDER_LOCATION')) {
                $lastData = $saver->removeOldItems($data,$count - (env('REDIS_MAX_ORDER_LOCATION') + 1));

                if (!$lastData) {
                    throw new \Exception('Saver error');
                }

                $orderLocation = OrderLocation::query()
                    ->where('order_id', $data['order_id'])
                    ->where('driver_id', $data['driver_id'])
                    ->first();

                SaveLastOrderLocationJob::dispatch($orderLocation, array_merge($data, $lastData), $order)
                    ->onQueue('last_location');

                $saver->deleteAllItems($data);
            }

            return responseOk();
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
