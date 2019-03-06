<?php

namespace Geocode\Laravel\Providers;

use Geocode\Laravel\Models\Query\GeocodeQuery;
use Illuminate\Support\Collection;

class Aggregator implements Provider
{
    /**
     * @var Provider[]
     */
    private $providers = [];

    /**
     * @param GeocodeQuery $query
     * @return Collection
     */
    public function geocode(GeocodeQuery $query): Collection
    {
        foreach ($this->providers as $provider) {
            try {
                $result = $provider->geocode($query);

                if (!$result->isEmpty()) {
                    return $result;
                }
            } catch (\Throwable $e) {

            }
        }
    }

    /**
     * @param GeocodeQuery $query
     * @return Collection
     */
    public function suggest(GeocodeQuery $query): Collection
    {
        foreach ($this->providers as $provider) {
            try {
                $result = $provider->suggest($query);

                if (!$result->isEmpty()) {
                    return $result;
                }
            } catch (\Throwable $e) {

            }
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'aggregator';
    }

    /**
     * @param Collection $providers
     * @return Aggregator
     */
    public function registerProvidersFromConfig(Collection $providers): self
    {
        $this->providers = $this->getProvidersFromConfiguration($providers);

        return $this;
    }

    /**
     * @param Collection $providers
     * @return array
     */
    protected function getProvidersFromConfiguration(Collection $providers) : array
    {
        $providers = $providers->map(function ($arguments, $provider) {
            $reflection = new \ReflectionClass($provider);

            return $reflection->newInstanceArgs($arguments);
        });

        return $providers->toArray();
    }
}
