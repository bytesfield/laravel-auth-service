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


        return $this->success('Registration successfully', array($user));
    }
}
