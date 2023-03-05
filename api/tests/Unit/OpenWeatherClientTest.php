<?php

namespace Tests\Unit;

use App\Client\OpenWeatherClient;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\AbstractUnitTestCase;

class OpenWeatherClientTest extends AbstractUnitTestCase
{
    private const CLIENT_RESPONSE = [
        "coord" => ["lon" => 10, "lat" => 10],
        "weather" => [
            [
                "id" => 501,
                "main" => "Rain",
                "description" => "moderate rain",
                "icon" => "10d",
            ],
        ],
        "base" => "stations",
        "main" => [
            "temp" => 298.48,
            "feels_like" => 298.74,
            "temp_min" => 297.56,
            "temp_max" => 300.05,
            "pressure" => 1015,
            "humidity" => 64,
            "sea_level" => 1015,
            "grnd_level" => 933,
        ],
        "visibility" => 10000,
        "wind" => ["speed" => 0.62, "deg" => 349, "gust" => 1.18],
        "rain" => ["1h" => 3.16],
        "clouds" => ["all" => 100],
        "dt" => 1661870592,
        "sys" => [
            "type" => 2,
            "id" => 2075663,
            "country" => "IT",
            "sunrise" => 1661834187,
            "sunset" => 1661882248,
        ],
        "timezone" => 7200,
        "id" => 3163858,
        "name" => "Zocca",
        "cod" => 200,
    ];
    public function testGetCurrentCachedWeatherDataSucceed(): void
    {
        Http::preventStrayRequests();

        /** @var Repository $cache */
        $cache = $this->mock(Repository::class,
            function (MockInterface $mock) {
            $mock->shouldReceive('has')
                ->with('weather-10-10')
                ->andReturn(true);
            $mock->shouldReceive('get')
                ->with('weather-10-10')
                ->andReturn(self::CLIENT_RESPONSE);
        });
        /** @var PendingRequest $request */
        $request = $this->mock(PendingRequest::class);

        $client = new OpenWeatherClient($request,$cache);
        $response = $client->getCurrentWeatherData('10', '10');
        $this->assertEquals(self::CLIENT_RESPONSE, $response);
    }

    public function testGetCurrentNonCachedWeatherDataSucceed(): void
    {
        Http::preventStrayRequests();

        /** @var ConfigRepository $config */
        $config = $this->app->get(ConfigRepository::class);
        $config->set('services.openweather.base_url', 'http://localhost.oi');

        /** @var Repository $cache */
        $cache = $this->mock(Repository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('has')
                    ->with('weather-10-10')
                    ->andReturn(false);
            });
        /** @var PendingRequest $request */
        $request = $this->mock(PendingRequest::class, function (MockInterface $mock)  {
            $mock->shouldReceive('withOptions')
                ->with([
                    'base_uri' => 'http://localhost.oi/'
                ])
                ->once()
                ->andReturn(new PendingRequest());

           $mock->shouldReceive('fake')
               ->once()
               ->andReturn(self::CLIENT_RESPONSE, 200);
        });

        $client = new OpenWeatherClient($request,$cache);
        $response = $client->getCurrentWeatherData('10', '10');
        $this->assertEquals(self::CLIENT_RESPONSE, $response);
    }
}
