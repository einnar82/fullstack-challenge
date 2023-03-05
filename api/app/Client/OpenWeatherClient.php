<?php

namespace App\Client;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OpenWeatherClient implements WeatherDataClientInterface
{
    public function __construct(private PendingRequest $openWeatherClient)
    {
    }

    public function getCurrentWeatherData(string $lat,string $lon): array
    {
        $cacheName =  $this->cacheName($lat,$lon);

        if (Cache::has($cacheName)) {
            return Cache::get($cacheName);
        }

        $response =  $this->request()->get('2.5/weather', [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => \config('services.openweather.api_key'),
            'units' => 'metric'
        ]);

        Cache::put($cacheName, $response->json(), 600);
        return $response->json();
    }

    private function cacheName(string $lat, string $lon): string
    {
        return \sprintf('weather-%s-%s', $lat, $lon);
    }

    private function request(): PendingRequest
    {
        return $this->openWeatherClient
            ->baseUrl(\config('services.openweather.base_url'));
    }
}
