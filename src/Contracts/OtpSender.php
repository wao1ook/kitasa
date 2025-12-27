<?php

namespace Emanate\Kitasa\Contracts;

interface OtpSender
{
    public function send(string $phoneNumber, string $otp): void;
}
