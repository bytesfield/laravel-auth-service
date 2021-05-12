<?php

namespace App\Http\Controllers\Auth;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Actions\Auth\RegisterAction;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{
    use ApiResponse;
    /**
     * Creates a new user.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @param \App\Http\Actions\Business\RegisterAction $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $user = $action->execute($request);

        return $this->success('Registration successfully', array($user));
    }
}
