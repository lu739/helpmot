<?php

declare(strict_types=1);

namespace App\UseCases\OnboardingUser\Create\Dto;

use App\Enum\UserRole;

class CreateOnboardingUserDto
{
    private ?int $id;
    private string $userId;
    private ?string $name;
    private string $phone;
    private UserRole $role;
    private string $password;
    private ?int $phoneСode;
    private ?string $phoneСodeDatetime;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getUserId(): string
    {
        return $this->userId;
    }
    public function setUserId(string $userId): CreateOnboardingUserDto
    {
        $this->userId = $userId;
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
    public function getPhoneСodeDatetime(): ?string
    {
        return $this->phoneСodeDatetime;
    }

    public function setPhoneСodeDatetime(?string $phoneСodeDatetime): self
    {
        $this->phoneСodeDatetime = $phoneСodeDatetime;
        return $this;
    }

    public function getPhoneСode(): ?int
    {
        return $this->phoneСode;
    }

    public function setPhoneСode(?int $phoneСode): self
    {
        $this->phoneСode = $phoneСode;
        return $this;
    }
}
