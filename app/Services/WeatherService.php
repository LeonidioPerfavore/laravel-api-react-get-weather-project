<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Redis;

class WeatherService
{
    public static function getWeatherFromRedis($userId)
    {
        $redis = Redis::connection();
        return json_decode($redis->get($userId), true);
    }

    public static function saveWeatherInRedis($userId, $weather)
    {
        $redis = Redis::connection();
        $redis->set($userId, json_encode($weather));
        $redis->expire($userId, 3600);
    }

    public static function getWeatherData($ip, $userId): array
    {
        $weatherFromRedis = self::getWeatherFromRedis($userId);
        if ($weatherFromRedis) {
            return ['error' => false, 'data' => $weatherFromRedis];
        }

        $location = LocationService::getLocation($ip);

        $client = new Client();

        try {
            $response = $client->get('https://api.openweathermap.org/data/2.5/onecall', [
                'query' => [
                    'lat' => $location->latitude,
                    'lon' => $location->longitude,
                    'appid' => env('OPEN_WEATHER_API_KEY'),
                    'exclude' => 'current,minutely,hourly,alerts',
                    'units' => 'metric'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $dayData = $data['daily'][0];

            $output = [
                'temp' => $dayData['temp']['day'],
                'pressure' => $dayData['pressure'],
                'humidity' => $dayData['humidity'],
                'temp_min' => $dayData['temp']['min'],
                'temp_max' => $dayData['temp']['max'],
            ];

            self::saveWeatherInRedis($userId, $output);

            return ['error' => false, 'data' => $output];

        } catch (RequestException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}