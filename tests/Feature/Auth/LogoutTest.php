<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testUserCanLogOutSuccessfully()
    {
        $this->authUser();

        $response = $this->post(route('logout'));

        $response->assertStatus(200)->assertExactJson([
            'message' => 'User successfully logged out.',
            'status' => 'success',
            'statusCode' => 200,
        ]);
    }

    public function testUserCanNotLogOutWithInvalidToken()
    {
        $invalidToken = $this->faker->md5;

        $headers = [
            'Accept' => 'application/json',
            'AUTHORIZATION' => 'Bearer ' . $invalidToken,
        ];
        $this->post(route('logout'), [], $headers)->assertStatus(401);
    }
}
