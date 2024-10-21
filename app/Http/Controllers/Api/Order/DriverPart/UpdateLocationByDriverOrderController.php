<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\DriverPart\UpdateLocationRequest;
use App\Models\Order;
use App\Models\OrderLocation;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class UpdateLocationByDriverOrderController extends Controller
{
    public function __invoke(Order $order, UpdateLocationRequest $request)
    {
        $data = $request->validated();

        $data['order_id'] = $order->id;
        $data['driver_id'] = $order->driver_id;
        $data['datetime'] = Carbon::createFromFormat('Y-m-d H:i:s', $data['datetime']);

        try {
            // Сериализуем объекты в JSON-строки
            $jsonData = json_encode($data);

            // Сохраняем JSON-строки в Redis Sorted Set с ключом "orders:" и значениями поля "datetime" как score
            Redis::zadd(
                'orders:' . $data['order_id'],
                $data['datetime']->timestamp,
                $jsonData
            );

            // Получаем количество записей в Redis Sorted Set
            $count = Redis::zcard('orders:' . $data['order_id']);

            // Получаем все элементы из Redis Sorted Set, отсортированные по возрастанию значения поля "datetime"
            $cachedData = Redis::zrange(
                'orders:' . $data['order_id'],
                0, -1,
                ['withscores' => true]
            );

            // Если количество записей больше REDIS_MAX_ORDER_LOCATION, удаляем лишние записи
            if ($count > env('REDIS_MAX_ORDER_LOCATION')) {
                Redis::zremrangebyrank('orders:' . $data['order_id'], 0, $count - (env('REDIS_MAX_ORDER_LOCATION') + 1));

                end($cachedData);
                $lastData = json_decode(key($cachedData), true);

                $orderLocation = OrderLocation::query()
                    ->where('order_id', $data['order_id'])
                    ->where('driver_id', $data['driver_id']);

                if ($orderLocation->exists()) {
                    $orderLocation->update([
                        'datetime' => $lastData['datetime'],
                        'last_location' => json_encode($lastData['last_location']),
                    ]);
                } else {
                    $orderLocation->create([
                        'order_id' => $data['order_id'],
                        'driver_id' => $data['driver_id'],
                        'datetime' => $lastData['datetime'],
                        'last_location' => json_encode($lastData['last_location']),
                        'start_location' => $order->location_start
                    ]);
                }

                Redis::del('orders:' . $data['order_id']);
            }

            return responseOk();
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
