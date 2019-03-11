<?php

declare(strict_types=1);

namespace Geocoding\Laravel\Models\Query;

use Geocoding\Laravel\Exceptions\InvalidArgument;
use Geocoding\Laravel\Models\QueryGroup;

class SuggestQuery implements Query
{
    /**
     * The address or text that should be suggested.
     *
     * @var string
     */
    private $group_by;

    /**
     * The address or text that should be suggested.
     *
     * @var string
     */
    private $text;

    /**
     * @var string|null
     */
    private $locale;

    /**
     * @var int
     */
    private $limit = 10;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string $text
     */
    private function __construct(string $text)
    {
        if (empty($text)) {
            throw new InvalidArgument('Geocode query cannot be empty');
        }

        $this->text = $text;
        $this->group_by = QueryGroup::GROUP_BY_ADDRESS;
    }

    /**
     * @param string $text
     *
     * @return SuggestQuery
     */
    public static function create(string $text): self
    {
        return new self($text);
    }

    /**
     * @param string $text
     *
     * @return SuggestQuery
     */
    public function withGroupBy(string $text): self
    {
        $new = clone $this;
        $new->group_by = $text;

        return $new;
    }

    /**
     * @param int $limit
     *
     * @return SuggestQuery
     */
    public function withLimit(int $limit): self
    {
        $new = clone $this;
        $new->limit = $limit;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return SuggestQuery
     */
    public function withText(string $text): self
    {
        $new = clone $this;
        $new->text = $text;

        return $new;
    }

    /**
     * @param string $locale
     *
     * @return SuggestQuery
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
     * @return SuggestQuery
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
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
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
        return sprintf('GeocodeQuery: %s', json_encode([
            'text' => $this->getText(),
            'locale' => $this->getLocale(),
            'limit' => $this->getLimit(),
            'group_by' => $this->getGroupBy(),
            'data' => $this->getAllData(),
        ]));
    }
}
