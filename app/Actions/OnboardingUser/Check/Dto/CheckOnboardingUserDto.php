<?php

declare(strict_types=1);

namespace App\Actions\OnboardingUser\Check\Dto;

use App\Enum\UserRole;
use Illuminate\Http\Request;

class CheckOnboardingUserDto
{
    protected string $caseType;
    public function __construct(
        protected string $phone,
        protected UserRole $role,
        protected ?int $onboardingId,
        protected ?int $phoneCode = null,
        protected ?string $phoneCodeDatetime = null,
    )
    {
    }


    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->get('phone'),
            UserRole::from($request->get('role')),
            $request->get('onboarding_id'),
            $request->get('phone_code'),
            $request->get('phone_code_datetime'),
        );
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function getPhoneCode(): ?int
    {
        return $this->phoneCode;
    }

    public function getPhoneCodeDatetime(): ?string
    {
        return $this->phoneCodeDatetime;
    }
    public function getOnboardingId(): ?int
    {
        return $this->onboardingId;
    }

    public function getCaseType(): string
    {
        return $this->caseType;
    }

    public function setCaseType(string $caseType): self
    {
        $this->caseType = $caseType;

        return $this;
    }
}
