<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\JsonResponse;

class RefreshTokenController extends Controller
{
    use JsonResponse;

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    protected function createNewToken($token)
    {
        $data = [
            'token' => $token,
            'expires_at' => now()->addSeconds(auth()->factory()->getTTL() * 60)->timestamp,
            'token_type' => 'Bearer',
        ];
        return $this->success('Token Refreshed Successfully', $data);
    }
}
