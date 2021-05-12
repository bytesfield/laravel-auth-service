<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\PasswordResetMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use  RefreshDatabase;

    public function testCanNotSendRestLinkIfEmailIsNotInputted()
    {

        $this->json('POST', route('password.forgot'), ['Content-Type' => 'application/json'])
            ->assertStatus(422)
            ->assertJson(['status' => 'failed']);
    }

    public function testCanNotSendRestLinkIfEmailNotExist()
    {
        User::factory()->create();

        $this->json('POST', route('password.forgot'), ['email' => '4684648476@not_exist.com'], ['Content-Type' => 'application/json'])
            ->assertStatus(404)
            ->assertJson(['status' => 'failed']);
    }

    public function testCanSendRestLinkWhenEmailExist()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'example@test.com']);

        $this->json('POST', route('password.forgot'), ['email' => $user->email], ['Content-Type' => 'application/json'])
            ->assertStatus(200)
            ->assertJson(['status' => 'success']);

        Mail::assertSent(PasswordResetMail::class);
    }
}
