<?php

declare(strict_types=1);

namespace Geocode;

use Geocode\Facades\Geocode;
use Geocode\Providers\Aggregator;
use Illuminate\Support\ServiceProvider;

class GeocodeServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $configPath = __DIR__ . '/../../config/geocode.php';

        $this->publishes(
            [ $configPath => base_path('config/geocode.php') ],
            'config'
        );

        $this->mergeConfigFrom($configPath, 'geocode');
    }

    public function register()
    {
        $this->app->alias('Geocode', Geocode::class);

        $this->app->singleton(Aggregator::class, function () {
            return (new Aggregator())
                ->registerProvidersFromConfig(collect(config('geocode.providers')));
        });

        $this->app->bind('geocode', Aggregator::class);
    }
}