<?php

namespace App\Services\SaveLocation\Interfaces;

interface SaverLocationInterface
{
    public function saveItem(array $data): int;
    public function removeOldItems(array $data, int $quantity): ?array;
    public function deleteAllItems(array $data): void;
    public function getSortedItems(int $orderId): ?array;
    public function getLastItem(int $orderId): ?array;
}
