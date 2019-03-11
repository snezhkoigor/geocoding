<?php

namespace Geocode\Laravel\Providers;

use Geocode\Laravel\Models\Query\GeocodeQuery;
use Geocode\Laravel\Models\Query\SuggestQuery;
use Geocode\Laravel\Resources\Address;
use Illuminate\Support\Collection;
use Geocode\Laravel\Exceptions\InvalidServerResponse;

class Aggregator implements Provider
{
    /**
     * @var Provider[]
     */
    private $providers = [];

    /**
     * @param GeocodeQuery $query
     * @return Address|null
     */
    public function geocode(GeocodeQuery $query): ?Address
    {
        foreach ($this->providers as $provider) {
            try {
                $result = $provider->geocode($query);

                if ($result instanceof Address) {
                    return $result;
                }
            } catch (\Throwable $e) {
                throw InvalidServerResponse::create('Provider "' . $provider->getName() . '" could not geocode address: "' . $query->getText() . '".');
            }
        }

        return null;
    }

    /**
     * @param SuggestQuery $query
     * @return Collection
     */
    public function suggest(SuggestQuery $query): Collection
    {
        foreach ($this->providers as $provider) {
            try {
                $result = $provider->suggest($query);

                if (!$result->isEmpty()) {
                    return $result;
                }
            } catch (\Throwable $e) {
                throw InvalidServerResponse::create('Provider "' . $provider->getName() . '" could not suggest address: "' . $query->getText() . '".');
            }
        }

        return collect([]);
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
