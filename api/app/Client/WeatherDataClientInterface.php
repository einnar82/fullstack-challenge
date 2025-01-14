<?php

namespace App\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface WeatherDataClientInterface
{
    public function getCurrentWeatherData(string $lat, string $lon): array;
}
