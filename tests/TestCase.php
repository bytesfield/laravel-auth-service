<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    /**
     * Create a user and authenticate the user.
     *
     * @param null $user
     *
     * @return \App\Models\User
     */
    public function authUser($user = null): User
    {
        $user = $user ?? User::factory()->create();

        $this->be($user);

        return $user;
    }

    /**
     * Authenticate a user and set the JWT Token as Authorization.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param null $driver
     *
     * @return $this
     */
    public function be(Authenticatable $user, $driver = null): self
    {
        $token = auth()->login($user);
        $this->withHeader('Authorization', 'Bearer ' . $token);

        return $this;
    }
}
