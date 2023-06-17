<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function getWeather(WeatherRequest $request): JsonResponse
    {
        $weather = WeatherService::getWeatherData($request->header('clientIp'), $request->get('user')->id);

        $status = $weather['error'] ? 201 : 200;
        $message = $weather['error'] ? $weather['message'] : 'Weather received';
        $data = $weather['error'] ? null : $weather['data'];

        return response()->json(['message' => $message, 'user' => $request->get('user'), 'main' => $data], $status);
    }
}