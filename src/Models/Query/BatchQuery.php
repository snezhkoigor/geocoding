<?php

declare(strict_types=1);

namespace Geocoding\Laravel\Models\Query;

use Geocoding\Laravel\Exceptions\InvalidArgument;
use Geocoding\Laravel\Models\QueryGroup;
use Illuminate\Support\Collection;

class BatchQuery implements Query
{
    /**
     * The address or text that should be geocoded.
     *
     * @var string
     */
    private $group_by;

    /**
     * Collection of GeocodeQuery and ReverseQuery.
     *
     * @var Collection
     */
    private $queries;

    /**
     * @var string|null
     */
    private $locale;

    /**
     * @var int
     */
    private $limit = 1;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param Collection $queries
     */
    private function __construct(Collection $queries)
    {
        if ($queries->count() === 0) {
            throw new InvalidArgument('Batch query cannot be empty. Set queries.');
        }

        $this->queries = $queries;
    }

    /**
     * @param array $addresses = ["спб", "мск", {"latitude", "longitude"}, "самара"]
     *
     * @return BatchQuery
     */
    public static function create(array $addresses): self
    {
        $queries = collect($addresses)->map(function ($address) {
            $query = null;
            if (is_string($address)) {
                $query = GeocodeQuery::create($address);
            } else if (!empty($address['latitude']) && !empty($address['longitude'])) {
                $query = ReverseQuery::create($address['latitude'], $address['longitude']);
            }

            if ($query instanceof Query) {
                return $query;
            }

            return null;
        })->reject(function($value) { return empty($value); });

        return new self($queries);
    }

    /**
     * @param string $text
     *
     * @return BatchQuery
     */
    public function withGroupBy(string $text): self
    {
        $new = clone $this;
        $new->queries = $new->queries->map(function (Query $query) use ($text) {
            return $query->withGroupBy($text);
        });
        $new->group_by = $text;

        return $new;
    }

    /**
     * @param integer $limit
     * @return BatchQuery
     */
    public function withLimit(int $limit): self
    {
        return $this;
    }

    /**
     * @param string $locale
     *
     * @return BatchQuery
     */
    public function withLocale(string $locale): self
    {
        $new = clone $this;
        $new->queries = $new->queries->map(function (Query $query) use ($locale) {
            return $query->withLocale($locale);
        });
        $new->locale = $locale;

        return $new;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return BatchQuery
     */
    public function withData(string $name, $value): self
    {
        $new = clone $this;
        $new->data[$name] = $value;

        return $new;
    }

    /**
     * @return string
     */
    public function getGroupBy(): string
    {
        return $this->group_by;
    }

    /**
     * @return Collection
     */
    public function getQueries(): Collection
    {
        return $this->queries;
    }

    /**
     * @return string|null
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param string     $name
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getData(string $name, $default = null)
    {
        if (!array_key_exists($name, $this->data)) {
            return $default;
        }

        return $this->data[$name];
    }

    /**
     * @return array
     */
    public function getAllData(): array
    {
        return $this->data;
    }

    /**
     * String for logging. This is also a unique key for the query
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('BatchQuery: %s', json_encode([
            'queries' => $this->getQueries()->map(function (Query $query) {
                return $query->__toString();
            }),
            'locale' => $this->getLocale(),
            'limit' => $this->getLimit(),
            'group_by' => $this->getGroupBy(),
            'data' => $this->getAllData(),
        ]));
    }
}
