<?php

use Geocoding\Laravel\Providers\DaData;

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
            'proxy' => env('DADATA_PROXY_IP', null),
            'proxy_port' => env('DADATA_PROXY_PORT', 80)
        ]
    ]
];
