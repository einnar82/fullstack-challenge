<?php

namespace App\Http\Controllers\API;

use App\Client\WeatherDataClientInterface;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    private const CACHE_NAME = 'users-list';

    public function __construct(private WeatherDataClientInterface $client)
    {
    }

    public function index()
    {
        if (Cache::has(self::CACHE_NAME)) {
            return response()->json(['users' => Cache::get(self::CACHE_NAME)]);
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

        Cache::put(self::CACHE_NAME, $usersList, 600);
        return \response()->json(['users' => $usersList]);
    }
}
