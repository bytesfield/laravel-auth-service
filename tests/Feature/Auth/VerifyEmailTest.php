<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testUserCanVerifyEmail()
    {
        $user = User::factory()->create(['email_token' => $this->faker->md5]);

        $response = $this->get(route('verify-email', ['email_token' => $user->email_token]));

        $response->assertStatus(200)->assertJson(['status' => 'success']);
    }


    public function testCanNotVerifyAnEmailWithAlreadyUsedToken()
    {
        User::factory()->create(['email_token' => null]);
        $email_token = $this->faker->md5;

        $response = $this->get(route('verify-email', ['email_token' => $email_token]));

        $response->assertStatus(400)
            ->assertJson(['status' => 'failed']);
    }
}
