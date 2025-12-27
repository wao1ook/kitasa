<?php

// config for Emanate/Kitasa
return [
    'phone_column' => 'phone_number',

    'otp' => [
        'expiry' => 10, // minutes
        'table' => 'kitasa_otps',
    ],
];
