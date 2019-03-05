<?php

declare(strict_types=1);

namespace Geocode\Laravel\Resources;

final class Address
{
    private $latitude;

    private $lontitude;

    private $address;

    private $provided_by;

    public function __constructor($provided_by, $address = null, $latitude = null, $lontitude = null)
    {
        $this->latitude = $latitude;
        $this->lontitude = $lontitude;
        $this->address = $address;
        $this->provided_by = $provided_by;
    }

    public function setProvidedBy(string $text)
    {
        $this->provided_by = $text;
    }

    public function setAddress(string $text)
    {
        $this->address = $text;
    }

    public function setLatitude(string $text)
    {
        $this->latitude = (float) $text;
    }

    public function setLontitude(string $text)
    {
        $this->lontitude = (float) $text;
    }
}
