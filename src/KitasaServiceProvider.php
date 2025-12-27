<?php

namespace Emanate\Kitasa;

use Emanate\Kitasa\Contracts\OtpSender;
use Emanate\Kitasa\Http\Livewire\Auth\ResetPassword;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class KitasaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('kitasa')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'add_phone_column_to_users_table',
                'create_kitasa_otps_table',
            ]);
    }

    public function packageBooted(): void
    {
        Livewire::component('filament.pages.auth.password-reset.reset-password', ResetPassword::class);
        Livewire::component('auth.password-reset.reset-password', ResetPassword::class);
        Livewire::component(\Filament\Pages\Auth\PasswordReset\ResetPassword::class, ResetPassword::class);

        Route::get('/kitasa/password-reset', ResetPassword::class)
            ->middleware(['web', 'signed'])
            ->name('kitasa.password-reset.reset');
    }

    public function packageRegistered(): void
    {
        $this->app->bind(OtpSender::class, config('kitasa.otp.sender', \Emanate\Kitasa\Services\LogOtpSender::class));
    }
}
