<?php

namespace App\Http\Controllers\Auth;

use App\Traits\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    use JsonResponse;

    public function authenticate(LoginRequest $request)
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
     * @return array
     */
    protected function respondWithToken(string $token)
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
    public function logout()
    {
        auth()->logout();

        return $this->success('User successfully logged out.');
    }
}
