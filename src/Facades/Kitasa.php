<?php

namespace Emanate\Kitasa\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Emanate\Kitasa\Kitasa
 */
class Kitasa extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Emanate\Kitasa\Kitasa::class;
    }
}
