<?php


namespace App\Service;

use Geocoder\Provider\Provider;

class MyGeoCoderService
{
    private $googleMapsGeocoder;

    public function __construct(Provider $googleMapsGeocoder)
    {
        $this->googleMapsGeocoder = $googleMapsGeocoder;
    }
}