<?php

declare(strict_types=1);

namespace Geocode\Model\Query;

use Geocode\Exceptions\InvalidArgument;

class GeocodeQuery implements Query
{
    /**
     * The address or text that should be geocoded.
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
    private $limit;

    /**
     * @var int
     */
    private $proxy;

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
    }

    /**
     * @param string $text
     *
     * @return GeocodeQuery
     */
    public static function create(string $text): self
    {
        return new self($text);
    }

    /**
     * @param string $text
     *
     * @return GeocodeQuery
     */
    public function withProxy(string $text): self
    {
        $new = clone $this;
        $new->proxy = $text;

        return $new;
    }

    /**
     * @param string $text
     *
     * @return GeocodeQuery
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
     * @return GeocodeQuery
     */
    public function withLocale(string $locale): self
    {
        $new = clone $this;
        $new->locale = $locale;

        return $new;
    }

    /**
     * @param int $limit
     *
     * @return GeocodeQuery
     */
    public function withLimit(int $limit): self
    {
        $new = clone $this;
        $new->limit = $limit;

        return $new;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return GeocodeQuery
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
            'data' => $this->getAllData(),
        ]));
    }
}