<?php

namespace App\Services\SaveLocation;

use App\Services\SaveLocation\Interfaces\SaverLocationInterface;
use Carbon\Carbon;
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

    public function getSortedItems(int $orderId): ?array
    {
        if (!Redis::exists('orders:' . $orderId)) {
            return null;
        }
        // Получаем все элементы из Redis Sorted Set, отсортированные по возрастанию значения поля "datetime"
        return Redis::zrange(
            'orders:' . $orderId, 0, -1, ['withscores' => true]
        );
    }

    public function getLastItem(int $orderId): ?array
    {
        $sortedData = $this->getSortedItems($orderId);

        if (!$sortedData) {
            return null;
        }

        end($sortedData);

        return array_merge(
            json_decode(key($sortedData), true),
            ['datetime' => Carbon::createFromTimestamp(current($sortedData))->format('Y-m-d H:i:s')]
        );
    }

    public function removeOldItems(array $data, int $quantity): ?array
    {
        // Получаем все элементы из Redis Sorted Set, отсортированные по возрастанию значения поля "datetime"
        $cachedData = $this->getSortedItems($data['order_id']);

        if (!$cachedData) {
            return null;
        }
        // Если количество записей больше REDIS_MAX_ORDER_LOCATION, удаляем лишние записи
        Redis::zremrangebyrank('orders:' . $data['order_id'], 0, $quantity);

        end($cachedData);
        return array_merge(
            json_decode(key($cachedData), true),
            ['datetime' => Carbon::createFromTimestamp(current($cachedData))->format('Y-m-d H:i:s')]
        );
    }

    public function deleteAllItems(array $data): void
    {
        Redis::del('orders:' . $data['order_id']);
    }
}
