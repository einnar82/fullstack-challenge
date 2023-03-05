<?php

namespace App\Client;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpenWeatherClient implements WeatherDataClientInterface
{
    public function __construct(private PendingRequest $openWeatherClient)
    {
    }

    public function getCurrentWeatherData(Request $request): JsonResponse
    {
        try {
            $response =  $this->request()->get('2.5/weather', [
                'lat' => $request->get('lat'),
                'lon' => $request->get('lon'),
                'appid' => \config('services.openweather.api_key'),
                'mode' => $request->get('mode', 'json'),
                'units' => $request->get('units', 'standard'),
                'lang' => $request->get('lang', 'en'),
            ]);

            return \response()->json($response->json());
        } catch (RequestException $exception) {
            $payload = json_decode($exception->getResponse()->getBody(), true);
            // This will return the actual exception
            return \response()->json($payload, 400);
        } catch (ConnectException $e) {
            //Catch the guzzle connection errors over here.These errors are something
            // like the connection failed or some other network error
            return \response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }

    private function request(): PendingRequest
    {
        return $this->openWeatherClient
            ->baseUrl(\config('services.openweather.base_url'));
    }
}
