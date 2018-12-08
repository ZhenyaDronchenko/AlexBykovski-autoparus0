<?php

namespace App\Provider\GeoLocation;

use App\Entity\GeoLocation;
use App\Type\GeoLocationType;

class GeoLocationProvider
{
    //https://tech.yandex.com/maps/doc/geocoder/desc/concepts/input_params-docpage/
    //https://ipstack.com/documentation

    const URL_CHECK_BY_COORDINATES = "https://geocode-maps.yandex.ru/1.x/?format=json&geocode=%s,%s";
    const URL_CHECK_BY_IP = "http://api.ipstack.com/%s?access_key=3357b5718c768651101c52494216b3f0";

    const CITY_KEY_YANDEX = "locality";
    const COUNTRY_KEY_YANDEX = "country";

    public function addGeoLocationToImage($coordinates, $ip)
    {
        if($coordinates){
            $longitude = $coordinates["longitude"];
            $latitude = $coordinates["latitude"];

            $geoLocationType = $this->getAddressByCoordinates($latitude, $longitude);
            $geoLocationType->setLongitude($longitude);
            $geoLocationType->setLatitude($latitude);
        }
        else{
            $geoLocationType = $this->getAddressByIp($ip);
        }

        $geoLocationType->setIp($ip);

        return $geoLocationType->createGeoLocation();
    }

    protected function getAddressByCoordinates($latitude, $longitude)
    {
        $geoLocation = new GeoLocationType();
        $url = sprintf(self::URL_CHECK_BY_COORDINATES, $longitude, $latitude);

        $response = json_decode(file_get_contents($url), true);

        if($this->isValidYandexResponse($response)){
            $address = $response["response"]["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["metaDataProperty"]["GeocoderMetaData"]["Address"];

            $geoLocation->setFullAddress($address["formatted"]);

            foreach ($address["Components"] as $component){
                if($component["kind"] === self::CITY_KEY_YANDEX){
                    $geoLocation->setCity($component["name"]);
                }

                if($component["kind"] === self::COUNTRY_KEY_YANDEX){
                    $geoLocation->setCountry($component["name"]);
                }
            }
        }

        return $geoLocation;
    }

    protected function getAddressByIp($ip)
    {
        $geoLocation = new GeoLocationType();
        $url = sprintf(self::URL_CHECK_BY_IP, $ip);

        $response = json_decode(file_get_contents($url), true);

        if($this->isValidIpStackResponse($response)){
            $country = $response["country_name"];
            $region = $response["region_name"];
            $city = $response["city"];
            $fullAddress = $country . ($region ? ', ' : '') . $region . ($city ? ', ' : '') . $city;


            $geoLocation->setFullAddress($fullAddress);
            $geoLocation->setCity($city);
            $geoLocation->setCountry($country);
            $geoLocation->setLatitude($response["latitude"]);
            $geoLocation->setLongitude($response["longitude"]);
        }

        return $geoLocation;
    }

    private function isValidYandexResponse($response)
    {
        return isset($response["response"]) && isset($response["response"]["GeoObjectCollection"]) &&
            isset($response["response"]["GeoObjectCollection"]["featureMember"]) &&
            is_array($response["response"]["GeoObjectCollection"]["featureMember"]) &&
            count($response["response"]["GeoObjectCollection"]["featureMember"]) &&
            isset($response["response"]["GeoObjectCollection"]["featureMember"][0]) &&
            isset($response["response"]["GeoObjectCollection"]["featureMember"][0]["GeoObject"]) &&
            isset($response["response"]["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["metaDataProperty"]) &&
            isset($response["response"]["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["metaDataProperty"]["GeocoderMetaData"]) &&
            isset($response["response"]["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["metaDataProperty"]["GeocoderMetaData"]["Address"]);
    }

    private function isValidIpStackResponse($response)
    {
        return is_array($response) && count($response) > 0 && isset($response["country_name"]) &&
            isset($response["region_name"]) && isset($response["city"]) && isset($response["latitude"]) &&
            isset($response["longitude"]);
    }
}