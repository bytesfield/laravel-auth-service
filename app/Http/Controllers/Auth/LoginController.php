<?php

namespace App\Http\Controllers\Auth;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    use ApiResponse;

    /**
     * Authenticate user.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            if ($request->remember_me === true) {
                $token = auth()->setTTL(10080)->attempt($credentials); // 1 week
            } else {
                $token = auth()->attempt($credentials); // 1 hour
            }
        } catch (JWTException $e) {
            return $this->serverError('Could not create token');
        }

        if (!$token) {
            return  $this->unauthorized('Invalid Credentials.');
        }


        return $this->respondWithToken($token);
    }

    /**
     * Format the response with the token.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        $user = auth()->user();
        $data = [
            'token' => $token,
            'expires_at' => now()->addSeconds(auth()->factory()->getTTL() * 60)->timestamp,
            'user' => new UserResource($user),
        ];
        return $this->success('Login Successful', $data);
    }

    /**
     * Log the user out [Invalidate the token].
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return $this->success('User successfully logged out.');
    }
}
