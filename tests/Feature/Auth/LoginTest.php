<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCanNotLoginWithoutCredentials()
    {
        $this->json('POST', route('login'))
            ->assertStatus(422)
            ->assertJson(['status' => 'failed']);
    }

    public function testCanNotLoginWithWrongPassword()
    {
        $user = User::factory()->create(['email' => 'example@test.com']);

        $payload = [
            'email' => $user->email,
            'password' => 'wrong_password',
        ];

        $this->json('POST', route('login'), $payload, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson(['status' => 'failed']);
    }

    public function testCanNotLoginIfEmailDoesNotExist()
    {
        User::factory()->create(['email' => 'example@test.com']);

        $payload = [
            'email' => 'wrong@test.com',
            'password' => 'password',
        ];

        $this->json('POST', route('login'), $payload, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson(['status' => 'failed']);
    }

    public function testCanLogin()
    {
        $user = User::factory()->create(['email' => 'example@test.com']);

        $payload = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->json('POST', route('login'), $payload, ['Accept' => 'application/json'])
            ->assertStatus(200);
        $this->assertAuthenticated();
    }
}
