<?php

namespace Emanate\Kitasa\Services;

use Emanate\Kitasa\Contracts\OtpSender;
use Illuminate\Support\Facades\Log;

class LogOtpSender implements OtpSender
{
    public function send(string $phoneNumber, string $otp): void
    {
        Log::info("Kitasa OTP for {$phoneNumber}: {$otp}");
    }
}
