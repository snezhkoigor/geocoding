<?php

return [
    'providers' => [
        \App\Services\Geocode\DaDataGeocodeService::class => [
            'token' => env('DADATA_TOKEN', ''),
            'proxy' => env('DADATA_PROXY_IP', null),
            'url' => env('DADATA_URL', null),
        ],
        \App\Services\Geocode\YandexGeocodeService::class => [
            'key' => env('YANDEX_ROUTE_BETWEEN_TWO_POINTS_KEY', ''),
            'proxy' => env('YANDEX_PROXY_IP', null),
        ]
    ]
];
