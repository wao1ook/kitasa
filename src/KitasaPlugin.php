<?php

namespace Emanate\Kitasa;

use Emanate\Kitasa\Http\Livewire\Auth\Login;
use Emanate\Kitasa\Http\Livewire\Auth\RequestPasswordReset;
use Filament\Contracts\Plugin;
use Filament\Panel;

class KitasaPlugin implements Plugin
{
    public function getId(): string
    {
        return 'kitasa';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->login(Login::class)
            ->passwordReset(RequestPasswordReset::class);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
