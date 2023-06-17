<?php

namespace App\Services;

use Stevebauman\Location\Facades\Location;

class LocationService
{
    public static function getLocation($ip)
    {
       return Location::get($ip);
    }
}