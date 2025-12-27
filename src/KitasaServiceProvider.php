<?php

namespace Emanate\Kitasa;


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
}
