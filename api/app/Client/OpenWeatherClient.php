<?php

namespace App\Client;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

class OpenWeatherClient implements WeatherDataClientInterface
{
    public function __construct(
        private PendingRequest $openWeatherClient,
        private Repository $cache
    ) {
    }

    public function getCurrentWeatherData(string $lat,string $lon): array
    {
        $cacheName =  $this->cacheName($lat,$lon);

        if ($this->cache->has($cacheName)) {
            return $this->cache->get($cacheName);
        }

        $response =  $this->request()->get('2.5/weather', [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => \config('services.openweather.api_key'),
            'units' => 'metric'
        ]);

        $this->cache->put($cacheName, $response->json(), 600);
        return $response->json();
    }

    private function cacheName(string $lat, string $lon): string
    {
        return \sprintf('weather-%s-%s', $lat, $lon);
    }

    private function request(): PendingRequest
    {
        return $this->openWeatherClient
            ->withOptions([
                'base_uri' => \config('services.openweather.base_url')
            ]);
    }
}
