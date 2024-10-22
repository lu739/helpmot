<?php

namespace App\Services\SaveLocation;

use App\Services\SaveLocation\Interfaces\SaverLocationInterface;
use Illuminate\Support\Facades\Redis;

class RedisSaveLocationService implements SaverLocationInterface
{

    public function saveItem(array $data): int
    {
        // Сохраняем JSON-строки в Redis Sorted Set с ключом "orders:" и значениями поля "datetime" как score
        Redis::zadd(
            'orders:' . $data['order_id'], $data['datetime']->timestamp, json_encode($data)
        );

        // Получаем количество записей в Redis Sorted Set
        return Redis::zcard('orders:' . $data['order_id']);
    }

    public function removeOldItems(array $data, int $quantity): array
    {
        // Получаем все элементы из Redis Sorted Set, отсортированные по возрастанию значения поля "datetime"
        $cachedData = Redis::zrange(
            'orders:' . $data['order_id'], 0, -1, ['withscores' => true]
        );
        // Если количество записей больше REDIS_MAX_ORDER_LOCATION, удаляем лишние записи
        Redis::zremrangebyrank('orders:' . $data['order_id'], 0, $quantity);

        end($cachedData);
        return json_decode(key($cachedData), true);
    }

    public function deleteAllItems(array $data): void
    {
        Redis::del('orders:' . $data['order_id']);
    }
}
