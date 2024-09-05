<?php

declare(strict_types=1);

namespace App\UseCases\OnboardingUser\Update\Dto;

use App\Enum\UserRole;

class UpdateOnboardingUserDto
{

    private ?int $id;
    private ?string $name;
    private string $password;
    private ?int $phoneCode;
    private ?string $phoneCodeDatetime;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
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

    public function setPhoneCode(?int $phoneCode): self
    {
        $this->phoneCode = $phoneCode;
        return $this;
    }
}
