<?php

namespace App\Http\Controllers\API;

use App\Client\WeatherDataClientInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpenweatherController extends Controller
{
    public function __construct(private WeatherDataClientInterface $client)
    {
    }

    public function getCurrentWeatherData(Request $request): JsonResponse
    {
        return $this->client->getCurrentWeatherData($request);
    }
}
