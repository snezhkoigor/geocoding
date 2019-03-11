<?php

declare(strict_types=1);

namespace Geocoding\Laravel;

use Geocoding\Laravel\Facades\Geocoding;
use Geocoding\Laravel\Providers\Aggregator;
use Illuminate\Support\ServiceProvider;

class GeocodeServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $configPath = __DIR__ . '/../config/geocoding.php';

        $this->publishes(
            [ $configPath => $this->configPath('geocode.php') ],
            'config'
        );

        $this->mergeConfigFrom($configPath, 'geocode');
    }

    public function register()
    {
        $this->app->alias('Geocode', Geocoding::class);

        $this->app->singleton(Aggregator::class, function () {
            return (new Aggregator())
                ->registerProvidersFromConfig(collect(config('geocode.providers')));
        });

        $this->app->bind('geocode', Aggregator::class);
    }

    protected function configPath(string $path = '') : string
    {
        if (function_exists('config_path')) {
            return config_path($path);
        }

        $pathParts = [
            app()->basePath(),
            'config',
            trim($path, '/'),
        ];

        return implode('/', $pathParts);
    }
}