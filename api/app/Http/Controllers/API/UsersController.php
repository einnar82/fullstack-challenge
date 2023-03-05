<?php

namespace App\Http\Controllers\API;

use App\Client\WeatherDataClientInterface;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Cache\Repository;

class UsersController extends Controller
{
    private const CACHE_NAME = 'users-list';

    public function __construct(
        private Repository $cache,
        private WeatherDataClientInterface $client
    ){
    }

    public function index()
    {
        if ($this->cache->has(self::CACHE_NAME)) {
            return response()->json(['users' => $this->cache->get(self::CACHE_NAME)]);
        }
        $users = User::query()->select(['id', 'name', 'email', 'latitude', 'longitude'])
            ->cursor()
            ->toArray();

        $usersList = [];
        foreach ($users as $user) {
            $response = $this->client->getCurrentWeatherData($user['latitude'], $user['longitude']);
            $user['weather'] = $response;
            $usersList[] = $user;
        }

        $this->cache->put(self::CACHE_NAME, $usersList, 600);
        return \response()->json(['users' => $usersList]);
    }
}
