<?php

use Geocode\Laravel\Providers\DaData;

return [
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    */
    'providers' => [
        DaData::class => [
            'token' => env('DADATA_TOKEN', ''),
            'proxy' => env('DADATA_PROXY_IP', null)
        ]
    ]
];
