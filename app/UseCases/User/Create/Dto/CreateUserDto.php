<?php

declare(strict_types=1);

namespace App\UseCases\User\Create\Dto;

use App\Enum\UserRole;

class CreateUserDto
{
    private ?int $id;
    private ?string $name;
    private string $email;
    private string $phone;
    private UserRole $role;
    private string $password;
    private ?string $phoneVerified;

    public function getPhoneVerified(): ?string
    {
        return $this->phoneVerified;
    }

    public function setPhoneVerified(?string $phoneVerified): self
    {
        $this->phoneVerified = $phoneVerified;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}
