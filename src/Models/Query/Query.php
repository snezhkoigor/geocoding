<?php

declare(strict_types=1);

namespace Geocode\Laravel\Models\Query;

interface Query
{
    /**
     * @param string $locale
     *
     * @return Query
     */
    public function withLocale(string $locale);

    /**
     * @param string $group_by
     *
     * @return Query
     */
    public function withGroupBy(string $group_by);

    /**
     * @param int $limit
     *
     * @return Query
     */
    public function withLimit(int $limit);

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return Query
     */
    public function withData(string $name, $value);

    /**
     * @return string|null
     */
    public function getLocale();

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @param string $name
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getData(string $name, $default = null);

    /**
     * @return array
     */
    public function getAllData(): array;

    /**
     * @return string
     */
    public function __toString();
}