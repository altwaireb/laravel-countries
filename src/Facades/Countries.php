<?php

namespace Altwaireb\Countries\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Altwaireb\Countries\Countries
 */
class Countries extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Altwaireb\Countries\Countries::class;
    }
}
