<?php

declare(strict_types=1);

namespace Geocode\Laravel\Providers;

use Geocode\Laravel\Models\Query\GeocodeQuery;
use Geocode\Laravel\Resources\Address;
use Geocode\Laravel\Resources\Resource;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Geocode\Laravel\Exceptions\InvalidServerResponse;

final class DaData implements Provider
{
    /**
     * @var mixed
     */
    protected $token;

    /**
     * @var mixed
     */
    protected $proxy;

    /**
     * Базовый url для автозаполнения
     */
    const SUGGEST_URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest';

    /**
     * Базовый url для геокодирования
     */
    const GEOCODE_URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest';

    /**
     * DaData constructor.
     *
     * @param $token
     * @param $proxy
     */
    public function __construct($token, $proxy = null)
    {
        $this->token = $token;
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
     * Specify
     *
     * @param GeocodeQuery $query
     * @return Collection
     */
    public function geocode(GeocodeQuery $query): Collection
    {
        $query = $query->withLimit(1);
        $data = $this->executeQuery($this->buildFinalUrl($query, self::GEOCODE_URL), $query);

        if ($data->count() && $data->first()->getLatitude()) {
            return $data;
        }

        return $data;
    }

    /**
     * @param GeocodeQuery $query
     * @return Collection
     */
    public function suggest(GeocodeQuery $query): Collection
    {
        $data = $this->executeQuery($this->buildFinalUrl($query, self::SUGGEST_URL), $query);

        if ($data->count()) {
            return $data->map(function ($item, $key) {
                return $item->getAddress();
            });
        }

        return $data;
    }

    /**
     * @param GeocodeQuery $query
     * @param string $url
     * @return Collection
     */
    private function executeQuery(string $url, GeocodeQuery $query): Collection
    {
        try {
            $response = (new Client())->post($url, $this->buildRequestData($query));
            $data = json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            throw InvalidServerResponse::create('Provider "' . $this->getName() . '" could not geocode address: "' . $query->getText() . '".');
        }

        if (empty($data['suggestions']) || \count($data['suggestions']) === 0) {
            return collect([]);
        }

        $result = [];
        foreach ($data['suggestions'] as $address) {
            $builder = new Address();

            $builder->setProvidedBy($this->getName());
            $builder->setLatitude($address['data']['geo_lat']);
            $builder->setLongitude($address['data']['geo_lon']);
            $builder->setAddress($address['unrestricted_value']);

            $result[] = $builder;
        }

        return collect($result);
    }

    /**
     * @param GeocodeQuery $query
     * @param $base_url
     * @return string
     */
    private function buildFinalUrl(GeocodeQuery $query, string $base_url)
    {
        $result = $base_url;

        switch ($query->getGroupBy()) {
            case GeocodeQuery::GROUP_BY_ADDRESS:
                $result .= '/address';
                break;

            case GeocodeQuery::GROUP_BY_CITY:
                $result .= '/address';
                break;
        }

        return $result;
    }

    /**
     * @param GeocodeQuery $query
     * @return array
     */
    private function buildRequestData(GeocodeQuery $query): array
    {
        $result = [
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

        if ($query->getGroupBy() === GeocodeQuery::GROUP_BY_CITY) {
            $result['from_bound'] = [
                'value' => 'city'
            ];
            $result['to_bound'] = [
                'value' => 'city'
            ];
        }

        return $result;
    }
}
