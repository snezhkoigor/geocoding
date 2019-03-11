<?php

declare(strict_types=1);

namespace Geocoding\Laravel;

use Geocoding\Laravel\Facades\Geocoding;
use Geocoding\Laravel\Providers\Aggregator;
use Illuminate\Support\ServiceProvider;

class GeocodingServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $configPath = __DIR__ . '/../config/geocoding.php';

        $this->publishes(
            [ $configPath => $this->configPath('geocoding.php') ],
            'config'
        );

        $this->mergeConfigFrom($configPath, 'geocoding');
    }

    public function register()
    {
        $this->app->alias('Geocoding', Geocoding::class);

        $this->app->singleton(Aggregator::class, function () {
            return (new Aggregator())
                ->registerProvidersFromConfig(collect(config('geocoding.providers')));
        });

        $this->app->bind('geocoding', Aggregator::class);
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