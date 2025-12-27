<?php

namespace Emanate\Kitasa;

use Emanate\Kitasa\Commands\KitasaCommand;
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
            ->hasViews()
            ->hasMigration('create_kitasa_table')
            ->hasCommand(KitasaCommand::class);
    }
}
