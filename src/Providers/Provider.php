<?php

declare(strict_types=1);

namespace Geocode\Laravel\Providers;

use Geocode\Laravel\Models\Query\GeocodeQuery;
use Geocode\Laravel\Models\Query\SuggestQuery;
use Geocode\Laravel\Resources\Address;
use Illuminate\Support\Collection;

interface Provider
{
    /**
     * @param GeocodeQuery $query
     *
     * @return Address|null
     *
     * @throws \Exception
     */
    public function geocode(GeocodeQuery $query): ?Address;

    /**
     * @param SuggestQuery $query
     *
     * @return Collection
     *
     * @throws \Exception
     */
    public function suggest(SuggestQuery $query): Collection;

    /**
     * Returns the provider's name.
     *
     * @return string
     */
    public function getName(): string;
}
