<?php

namespace Erenilhan\CierraPatch\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Erenilhan\CierraPatch\CierraPatch
 */
class CierraPatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Erenilhan\CierraPatch\CierraPatch::class;
    }
}
