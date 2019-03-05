<?php

namespace Geocode\Laravel\Providers;

use Geocode\Laravel\Model\Query\GeocodeQuery;
use Illuminate\Support\Collection;

class Aggregator implements Provider
{
    /**
     * @var Provider[]
     */
    private $providers = [];

    public function geocodeQuery(GeocodeQuery $query): Collection
    {
        foreach ($this->providers as $provider) {
            try {
                $result = $provider->geocodeQuery($query);

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

    public function registerProvidersFromConfig(Collection $providers): self
    {
        $this->providers = $this->getProvidersFromConfiguration($providers);

        return $this;
    }

    protected function getProvidersFromConfiguration(Collection $providers) : array
    {
        $providers = $providers->map(function ($arguments, $provider) {
            $reflection = new \ReflectionClass($provider);

            return $reflection->newInstance($arguments);
        });

        return $providers->toArray();
    }
}
