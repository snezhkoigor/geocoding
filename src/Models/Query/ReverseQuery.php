<?php

declare(strict_types=1);

namespace Geocoding\Laravel\Models\Query;

use Geocoding\Laravel\Exceptions\InvalidArgument;
use Geocoding\Laravel\Models\QueryGroup;

class ReverseQuery implements Query
{
    /**
     * The address or text that should be geocoded.
     *
     * @var string
     */
    private $group_by;

    /**
     * The latitude should be geocoded.
     *
     * @var string
     */
    private $latitude;

    /**
     * The longitude should be geocoded.
     *
     * @var string
     */
    private $longitude;

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
     * @param float $latitude
     * @param float $longitude
     */
    private function __construct(float $latitude, float $longitude)
    {
        if (empty($text)) {
            throw new InvalidArgument('Geocode query cannot be empty');
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->group_by = QueryGroup::GROUP_BY_ADDRESS;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return ReverseQuery
     */
    public static function create(float $latitude, float $longitude): self
    {
        return new self($latitude, $longitude);
    }

    /**
     * @param string $text
     *
     * @return ReverseQuery
     */
    public function withGroupBy(string $text): self
    {
        $new = clone $this;
        $new->group_by = $text;

        return $new;
    }

    /**
     * @param integer $limit
     * @return ReverseQuery
     */
    public function withLimit(int $limit): self
    {
        return $this;
    }

    /**
     * @param string $locale
     *
     * @return ReverseQuery
     */
    public function withLocale(string $locale): self
    {
        $new = clone $this;
        $new->locale = $locale;

        return $new;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return ReverseQuery
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
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
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
        return sprintf('ReverseQuery: %s', json_encode([
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'locale' => $this->getLocale(),
            'limit' => $this->getLimit(),
            'group_by' => $this->getGroupBy(),
            'data' => $this->getAllData(),
        ]));
    }
}
