<?php

namespace App\Actions\Order\ClientPart\Create\Dto;

use App\Enum\OrderStatus;
use App\Enum\OrderType;

class CreateActiveOrderDto
{
    public function __construct(
        public readonly string $client_id,
        public readonly OrderType $type,
        public readonly string $location_start,
        public readonly OrderStatus $status,
        public readonly ?string $client_comment,
    ) {
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function getType(): OrderType
    {
        return $this->type;
    }

    public function getLocationStart(): string
    {
        return $this->location_start;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function getClientComment(): ?string
    {
        return $this->client_comment;
    }

    public static function fromRequest(array $request): self
    {
        return new self(
            client_id: auth()->id(),
            type: OrderType::from($request['type']),
            location_start: json_encode($request['location_start']),
            status: OrderStatus::ACTIVE,
            client_comment: $request['comment'] ?? '',
        );
    }
}
