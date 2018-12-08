<?php

namespace App\Type;


use App\Entity\GeoLocation;

class GeoLocationType
{
    /**
     * @var string|null
     */
    private $latitude;

    /**
     * @var string|null
     */
    private $longitude;

    /**
     * @var string|null
     */
    private $ip;

    /**
     * @var string|null
     */
    private $fullAddress;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $country;

    /**
     * @return null|string
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param null|string $latitude
     */
    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return null|string
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param null|string $longitude
     */
    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return null|string
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param null|string $ip
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return null|string
     */
    public function getFullAddress(): ?string
    {
        return $this->fullAddress;
    }

    /**
     * @param null|string $fullAddress
     */
    public function setFullAddress(?string $fullAddress): void
    {
        $this->fullAddress = $fullAddress;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param null|string $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function createGeoLocation()
    {
        $geoLocation = new GeoLocation($this->ip);

        $geoLocation->setCity($this->city);
        $geoLocation->setCountry($this->country);
        $geoLocation->setFullAddress($this->fullAddress);
        $geoLocation->setLatitude($this->latitude);
        $geoLocation->setLongitude($this->longitude);

        return $geoLocation;
    }
}