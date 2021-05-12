<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCanRefreshToken()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $headers = [
            'Accept' => 'application/json',
            'AUTHORIZATION' => 'Bearer ' . $token,
        ];

        $this->post(route('refresh-token'), [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure(['data' => ['token', 'token_type', 'expires_at']]);
    }

    public function testCanNotRefreshTokenWithInvalidToken()
    {
        $invalidToken = $this->faker->md5;

        $headers = [
            'Accept' => 'application/json',
            'AUTHORIZATION' => 'Bearer ' . $invalidToken,
        ];

        $this->post(route('refresh-token'), [], $headers)->assertStatus(401);
    }
}
