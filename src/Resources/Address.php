<?php

declare(strict_types=1);

namespace Geocoding\Laravel\Resources;

final class Address
{
    /**
     * @var
     */
    private $latitude;

    /**
     * @var
     */
    private $longitude;

    /**
     * @var
     */
    private $address;

    /**
     * @var
     */
    private $provided_by;

    /**
     * @param $provided_by
     * @param null $address
     * @param null $latitude
     * @param null $longitude
     */
    public function __constructor($provided_by, $address = null, $latitude = null, $longitude = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->address = $address;
        $this->provided_by = $provided_by;
    }

    /**
     * @param string $text
     */
    public function setProvidedBy(string $text)
    {
        $this->provided_by = $text;
    }

    /**
     * @param string $text
     */
    public function setAddress(string $text)
    {
        $this->address = $text;
    }

    /**
     * @retrun string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param $text
     */
    public function setLatitude($text)
    {
        $this->latitude = (float) $text;
    }

    /**
     * @retrun float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param $text
     */
    public function setLongitude($text)
    {
        $this->longitude = (float) $text;
    }

    /**
     * @retrun float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'provided_by' => $this->provided_by,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'address' => $this->address
        ];
    }
}
