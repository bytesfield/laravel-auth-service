<?php

namespace App\Http\Actions\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Events\Auth\UserCreatedEvent;
use App\Http\Requests\Auth\RegisterRequest;


class RegisterAction
{
    /**
     * Action to register a user.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     *
     * @return \App\Models\User
     */
    public function execute(RegisterRequest $request): User
    {
        try {
            $user = $this->saveUser($request);

            UserCreatedEvent::dispatch($user);

            return $user;
        } catch (Exception $error) {

            report($error);

            abort(500, 'Unable to create an account');
        }
    }

    /**
     * Save user in database.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     *
     * @return \App\Models\User
     */
    protected function saveUser(RegisterRequest $request): User
    {
        return User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'email_token' => Str::random(30),
            'password' => Hash::make($request->password),
        ]);
    }
}
