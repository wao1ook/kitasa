<?php

return [
    'phone_column' => 'phone_number',

    'otp' => [
        'expiry' => 10,
        'table' => 'kitasa_otps',
        'sender' => \Emanate\Kitasa\Services\LogOtpSender::class,
    ],
];
