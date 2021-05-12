<?php

namespace Tests\Feature\Auth;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCanNotResetPasswordWithAnExpiredToken(): void
    {
        $user = User::factory()->create(['email' => $this->faker->safeEmail]);

        $email = $user->email;
        $passwordReset = PasswordReset::factory()->create([
            'email' => $email,
            'created_at' => now()->subHours(1),
        ]);

        $response = $this->post(route('password.reset'), $this->payload($email, $passwordReset->token));

        $response->assertStatus(400)
            ->assertJson(['status' => 'failed', 'message' => 'Invalid token or expired token']);
    }

    public function testCanNotResetPasswordWithTheOldPassword(): void
    {
        $password = 'Dassword@123';
        $user = User::factory()->create(['email' => $this->faker->safeEmail, 'password' => Hash::make($password)]);
        $email = $user->email;

        $passwordReset = PasswordReset::factory()->create([
            'email' => $email
        ]);

        $response = $this->post(route('password.reset'), $this->payload($email, $passwordReset->token, $password));

        $response->assertStatus(400)
            ->assertJson(['status' => 'failed', 'message' => "Sorry you can't use your old password"]);
    }

    public function testCanNotResetPasswordWithAnInvalidToken(): void
    {
        $user = User::factory()->create(['email' => $this->faker->safeEmail]);
        $email = $user->email;

        PasswordReset::factory()->create([
            'email' => $email,
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.reset'), $this->payload($email));

        $response->assertStatus(400)
            ->assertJson(['status' => 'failed', 'message' => 'Invalid token or expired token']);
    }

    public function testCanResetUserPasswordWithAValidToken(): void
    {
        $user = User::factory()->create(['email' => $this->faker->safeEmail]);
        $email = $user->email;

        $passwordReset = PasswordReset::factory()->create([
            'email' => $email,
        ]);

        $response = $this->post(route('password.reset'), $this->payload($email, $passwordReset->token));

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'message' => 'Password reset successfully']);
    }

    protected function payload(string $email = null, string $token = null, string $password = null): array
    {
        return [
            'token' => $token ?? $this->faker->md5,
            'email' => $email ?? $this->faker->safeEmail,
            'password' => $password ?? '@Secret@12345',
            'password_confirmation' => $password ?? '@Secret@12345',
        ];
    }
}
