<?php

declare(strict_types=1);

namespace Geocode\Laravel\Providers;

use Geocode\Laravel\Models\Query\GeocodeQuery;
use Illuminate\Support\Collection;

interface Provider
{
    /**
     * @param GeocodeQuery $query
     *
     * @return Collection
     *
     * @throws \Exception
     */
    public function geocode(GeocodeQuery $query): Collection;

    /**
     * @param GeocodeQuery $query
     *
     * @return Collection
     *
     * @throws \Exception
     */
    public function suggest(GeocodeQuery $query): Collection;

    /**
     * Returns the provider's name.
     *
     * @return string
     */
    public function getName(): string;
}
