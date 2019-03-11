<?php

namespace Geocoding\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Geocoding extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geocoding';
    }
}