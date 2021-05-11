<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Actions\Auth\RegisterAction;
use App\Http\Resources\Auth\UserResource;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{
    use JsonResponse;
    /**
     * Creates a new user.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @param \App\Http\Actions\Business\RegisterAction $action
     *
     * @return \App\Traits\JsonResponse
     */
    public function store(RegisterRequest $request, RegisterAction $action)
    {

        $user = $action->execute($request);

        $response = $this->respondWithToken($user/*, auth()->login($user->id)*/);

        return $this->success('Registration successfully', $response);
    }

    /**
     * Format the response with the token.
     *
     * @param \App\Models\User $user
     * @param string $token
     *
     * @return array
     */
    protected function respondWithToken(User $user/*, string $token*/): array
    {
        return [
            //'token' => $token,
            //'expires_at' => now()->addSeconds(auth()->factory()->getTTL() * 60)->timestamp,
            'user' => new UserResource($user),
        ];
    }
}
