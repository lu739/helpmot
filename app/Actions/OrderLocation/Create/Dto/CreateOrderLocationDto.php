<?php

declare(strict_types=1);

namespace App\Actions\OrderLocation\Create\Dto;


class CreateOrderLocationDto
{
    private int $order_id;
    private int $driver_id;
    private string $datetime;
    private array $last_location;
    private array $start_location;


    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): CreateOrderLocationDto
    {
        $this->order_id = $order_id;
        return $this;
    }

    public function getDriverId(): int
    {
        return $this->driver_id;
    }

    public function setDriverId(int $driver_id): CreateOrderLocationDto
    {
        $this->driver_id = $driver_id;
        return $this;
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function setDatetime(string $datetime): CreateOrderLocationDto
    {
        $this->datetime = $datetime;
        return $this;
    }

    public function getLastLocation(): array
    {
        return $this->last_location;
    }

    public function setLastLocation(array $last_location): CreateOrderLocationDto
    {
        $this->last_location = $last_location;
        return $this;
    }

    public function getStartLocation(): array
    {
        return $this->start_location;
    }

    public function setStartLocation(array $start_location): CreateOrderLocationDto
    {
        $this->start_location = $start_location;
        return $this;
    }
}
