<?php

namespace App\Services\ConfirmSms\Interfaces;

interface SmsUserInterface
{
    public function getPhone(): string;
    public function getPhoneCode(): int;
    public function getId(): string;
}
