<?php

declare(strict_types=1);

namespace Geocode\Laravel\Providers;

use Geocode\Laravel\Model\Query\GeocodeQuery;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

final class DaData implements Provider
{
    /**
     * @var mixed
     */
    protected $token;

    /**
     * @var mixed
     */
    protected $url;

    /**
     * @var mixed
     */
    protected $http_client;

    /**
     * DaData constructor.
     *
     * @param $token
     * @param $url
     */
    public function __construct($token, $url)
    {
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'dadata';
    }

    /**
     * @param GeocodeQuery $query
     * @return Collection
     */
    public function geocodeQuery(GeocodeQuery $query): Collection
    {
        return $this->executeQuery($query);
    }

    /**
     * @param GeocodeQuery $query
     * @return Collection
     */
    private function executeQuery(GeocodeQuery $query): Collection
    {
        $with_data = [
            'headers' => [
                'Authorization' => 'Token '.$this->token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'query' => [
                'q' => $query->getText(),
                'count' => $query->getLimit()
            ]
        ];

        $response = (new Client())->post($this->url, $with_data);

        $data = $response->getBody();

        return collect([]);
    }
}