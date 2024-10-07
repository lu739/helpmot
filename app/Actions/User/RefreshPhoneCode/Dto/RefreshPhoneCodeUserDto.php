<?php

declare(strict_types=1);

namespace App\Actions\User\RefreshPhoneCode\Dto;

use App\Enum\UserRole;
use Illuminate\Database\Eloquent\Model;

class RefreshPhoneCodeUserDto
{
    private ?int $id;
    private ?int $phoneCode;
    private ?string $phoneCodeDatetime;

    private Model $model;


    public function getPhoneCodeDatetime(): ?string
    {
        return $this->phoneCodeDatetime;
    }

    public function setPhoneCodeDatetime(?string $phoneCodeDatetime): self
    {
        $this->phoneCodeDatetime = $phoneCodeDatetime;
        return $this;
    }

    public function getPhoneCode(): ?int
    {
        return $this->phoneCode;
    }

    public function setPhoneCode(int $phoneCode): self
    {
        $this->phoneCode = $phoneCode;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }
}
