<?php

namespace App\Services\SaveLocation\Interfaces;

interface SaverLocationInterface
{
    public function saveItem(array $data): int;
    public function removeOldItems(array $data, int $quantity): array;
    public function deleteAllItems(array $data): void;
}
