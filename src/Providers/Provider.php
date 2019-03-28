<?php

declare(strict_types=1);

namespace Geocoding\Laravel\Providers;

use Geocoding\Laravel\Models\Query\BatchQuery;
use Geocoding\Laravel\Models\Query\GeocodeQuery;
use Geocoding\Laravel\Models\Query\ReverseQuery;
use Geocoding\Laravel\Models\Query\SuggestQuery;
use Geocoding\Laravel\Resources\Address;
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
     * @param ReverseQuery $query
     *
     * @return Address|null
     *
     * @throws \Exception
     */
    public function reverse(ReverseQuery $query): ?Address;

    /**
     * @param SuggestQuery $query
     *
     * @return Collection
     *
     * @throws \Exception
     */
    public function suggest(SuggestQuery $query): Collection;

    /**
     * @param BatchQuery $query
     *
     * @return Collection
     *
     * @throws \Exception
     */
    public function batch(BatchQuery $query): Collection;

    /**
     * Returns the provider's name.
     *
     * @return string
     */
    public function getName(): string;
}
