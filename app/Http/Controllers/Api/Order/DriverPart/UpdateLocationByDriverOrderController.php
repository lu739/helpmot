<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Actions\OrderLocation\Create\CreateLastLocationAction;
use App\Actions\OrderLocation\Create\Dto\CreateOrderLocationDto;
use App\Actions\OrderLocation\Update\Dto\UpdateOrderLocationDto;
use App\Actions\OrderLocation\Update\UpdateLastLocationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\DriverPart\UpdateLocationRequest;
use App\Models\Order;
use App\Models\OrderLocation;
use App\Services\SaveLocation\Interfaces\SaverLocationInterface;
use Carbon\Carbon;

class UpdateLocationByDriverOrderController extends Controller
{
    public function __invoke(Order $order, UpdateLocationRequest $request, SaverLocationInterface $saver)
    {
        $data = $request->validated();

        $data['order_id'] = $order->id;
        $data['driver_id'] = $order->driver_id;
        $data['datetime'] = Carbon::createFromFormat('Y-m-d H:i:s', $data['datetime']);

        try {
            $count = $saver->saveItem($data);

            if ($count > env('REDIS_MAX_ORDER_LOCATION')) {
                $lastData = $saver->removeOldItems($data,$count - (env('REDIS_MAX_ORDER_LOCATION') + 1));

                $orderLocation = OrderLocation::query()
                    ->where('order_id', $data['order_id'])
                    ->where('driver_id', $data['driver_id']);

                // Todo: Вынести в job
                if ($orderLocation->exists()) {
                    (new UpdateLastLocationAction())->handle((new UpdateOrderLocationDto())
                        ->setId($orderLocation->first()->id)
                        ->setDatetime($lastData['datetime'])
                        ->setLastLocation($lastData['last_location'])
                    );
                } else {
                    $dto = (new CreateOrderLocationDto())
                        ->setOrderId($data['order_id'])
                        ->setDriverId($data['driver_id'])
                        ->setDatetime($lastData['datetime'])
                        ->setLastLocation($lastData['last_location'])
                        ->setStartLocation($order->location_start);

                    (new CreateLastLocationAction())->handle($dto);
                }

                $saver->deleteAllItems($data);
            }

            return responseOk();
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
