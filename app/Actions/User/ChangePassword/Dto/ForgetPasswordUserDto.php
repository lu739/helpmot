<?php

declare(strict_types=1);

namespace App\Actions\User\ChangePassword\Dto;

use App\Enum\UserRole;

class ForgetPasswordUserDto
{
    private ?int $id;
    private string $phone;
    private string $newPassword;
    private ?int $phoneCode;
    private ?string $phoneCodeDatetime;
    private UserRole $role;


    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): self
    {
        $this->role = $role;
        return $this;
    }

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


    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
    public function setNewPassword(string $password): self
    {
        $this->newPassword = $password;
        return $this;
    }
}
