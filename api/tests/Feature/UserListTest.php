<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserListTest extends TestCase
{
    use RefreshDatabase;

    private const RESPONSE_STRUCTURE = [
      'id',
      'name',
      'latitude',
      'longitude',
      'weather'
    ];

    public function testListSucceed(): void
    {
        User::factory(1)->create();
        $response = $this->getJson('/');

        $response->assertOk();
        $response->assertJsonStructure([
            'users' => [
                '*' => self::RESPONSE_STRUCTURE
            ]
        ]);
    }
}
