<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\UserEmailVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResendVerificationEmailTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCanNotResendVerificationEmailToAnInvalidUserEmail()
    {
        User::factory()->create();

        $response = $this->post(route('resend-verification-email'), $this->payload('example@test.com'));

        $response->assertStatus(422)->assertJson(['status' => 'failed']);
    }

    public function testCanNotResendVerificationEmailToAVerifiedEmail()
    {
        $user = User::factory()->create(['email_token' => null]);

        $response = $this->post(route('resend-verification-email'), $this->payload($user->email));

        $response->assertStatus(400)->assertJson(['status' => 'failed']);
    }

    public function testCanResendVerificationEmailToUser()
    {
        Mail::fake();

        $user = User::factory()->create(['email_token' => $this->faker->md5]);

        $response = $this->post(route('resend-verification-email'), $this->payload($user->email));

        $response->assertStatus(200)->assertJson(['status' => 'success']);
        Mail::assertSent(UserEmailVerification::class);
    }

    protected function payload($email = null): array
    {
        return [
            'email' => $email ?? $this->faker->unique()->safeEmail,
        ];
    }
}
