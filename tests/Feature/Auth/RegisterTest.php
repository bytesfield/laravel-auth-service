<?php

namespace Tests\Feature\Auth;

use App\Events\Auth\UserCreatedEvent;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testUserCanRegisterWithValidInformation()
    {
        $this->withExceptionHandling();

        Event::fake();

        $payload = $this->payload();

        $response = $this->post(route('register'), $payload);

        $response->assertStatus(200)
            ->assertOk()
            ->assertJson(['status' => 'success']);

        Event::assertDispatched(UserCreatedEvent::class);
    }

    public function testUserCanNotRegisterWithInvalidInformation()
    {
        $this->withExceptionHandling();

        Event::fake();

        $payload = $this->payload();
        $payload['email'] = $this->faker->url;

        $response = $this->post(route('register'), $payload);

        $response->assertStatus(422)
            ->assertJson(['status' => 'failed']);

        Event::assertNotDispatched(UserCreatedEvent::class);
    }

    public function testUserCanNotRegisterWithWrongPassword()
    {
        $this->withExceptionHandling();

        Event::fake();

        $payload = $this->payload();
        $payload['password'] = $this->faker->name;
        $payload['password_confirmation'] = $this->faker->name;

        $response = $this->post(route('register'), $payload);

        $response->assertStatus(422)
            ->assertJson(['status' => 'failed']);

        Event::assertNotDispatched(UserCreatedEvent::class);
    }

    public function testUserCanNotRegisterWithPasswordThatDoesNotMatch()
    {
        $this->withExceptionHandling();

        Event::fake();

        $payload = $this->payload();
        $payload['password_confirmation'] = $this->faker->name;

        $response = $this->post(route('register'), $payload);

        $response->assertStatus(422)
            ->assertJson(['status' => 'failed']);

        Event::assertNotDispatched(UserCreatedEvent::class);
    }



    protected function payload(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'abrahamudele@gmail.com',
            'password' => 'Dassword@123',
            'password_confirmation' => 'Dassword@123',
        ];
    }
}
