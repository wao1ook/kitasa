<?php

namespace Emanate\Kitasa\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OtpService
{
    protected string $table;

    protected int $expiry;

    public function __construct()
    {
        $this->table = config('kitasa.otp.table', 'kitasa_otps');
        $this->expiry = config('kitasa.otp.expiry', 10);
    }

    public function generate(string $phoneNumber): string
    {
        $otp = (string) rand(100000, 999999);

        DB::table($this->table)->updateOrInsert(
            ['phone_number' => $phoneNumber],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes($this->expiry),
                'verified_at' => null,
                'updated_at' => Carbon::now(),
            ]
        );

        return $otp;
    }

    public function verify(string $phoneNumber, string $otp): bool
    {
        $record = DB::table($this->table)
            ->where('phone_number', $phoneNumber)
            ->where('otp', $otp)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('verified_at')
            ->first();

        if ($record) {
            DB::table($this->table)
                ->where('id', $record->id)
                ->update(['verified_at' => Carbon::now()]);

            return true;
        }

        return false;
    }

    public function isVerified(string $phoneNumber): bool
    {
        return DB::table($this->table)
            ->where('phone_number', $phoneNumber)
            ->whereNotNull('verified_at')
            ->where('verified_at', '>', Carbon::now()->subMinutes($this->expiry))
            ->exists();
    }
}
