<?php

declare(strict_types=1);

namespace App\Actions\User\ChangePassword\Dto;

use App\Enum\UserRole;

class RefreshPasswordUserDto
{
    private ?int $id;
    private string $newPassword;


    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;
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
}
