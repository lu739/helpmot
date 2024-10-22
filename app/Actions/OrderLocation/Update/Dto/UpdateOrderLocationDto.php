<?php

declare(strict_types=1);

namespace App\Actions\OrderLocation\Update\Dto;


class UpdateOrderLocationDto
{
    private int $id;
    private string $datetime;
    private array $last_location;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): UpdateOrderLocationDto
    {
        $this->id = $id;
        return $this;
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function setDatetime(string $datetime): self
    {
        $this->datetime = $datetime;
        return $this;
    }

    public function getLastLocation(): array
    {
        return $this->last_location;
    }

    public function setLastLocation(array $last_location): self
    {
        $this->last_location = $last_location;
        return $this;
    }
}
