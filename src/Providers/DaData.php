<?php

declare(strict_types=1);

namespace Geocode\Laravel\Providers;

use Geocode\Laravel\Models\Query\GeocodeQuery;
use Geocode\Laravel\Resources\Address;
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
    protected $proxy;

    /**
     * DaData constructor.
     *
     * @param $token
     * @param $url
     */
    public function __construct($token, $url, $proxy = null)
    {
        $this->token = $token;
        $this->url = $url;
        $this->proxy = $proxy;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'DaData.ru';
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
            'json' => [
                'query' => $query->getText(),
                'count' => $query->getLimit()
            ],
            'proxy' => $this->proxy
        ];

        try {
            $response = (new Client())->post($this->url, $with_data);
            $data = json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            throw InvalidServerResponse::create($query);
        }

        if (empty($data['suggestions']) || \count($data['suggestions']) === 0) {
            return collect([]);
        }

        $result = [];
        foreach ($data['suggestions'] as $address) {
            $builder = new Address();
            $builder->setProvidedBy($this->getName());
            $builder->setLatitude($address['data']['geo_lat']);
            $builder->setLontitude($address['data']['geo_lon']);
            $builder->setAddress($address['unrestricted_value']);

            $result[] = $builder;
        }

        return collect($result);
    }
}