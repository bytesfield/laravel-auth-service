<?php

namespace App\Http\Controllers\Auth;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class RefreshTokenController extends Controller
{
    use ApiResponse;

    /**
     * Refresh token
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Create new token
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token): JsonResponse
    {
        $data = [
            'token' => $token,
            'expires_at' => now()->addSeconds(auth()->factory()->getTTL() * 60)->timestamp,
            'token_type' => 'Bearer',
        ];
        return $this->success('Token Refreshed Successfully', $data);
    }
}
