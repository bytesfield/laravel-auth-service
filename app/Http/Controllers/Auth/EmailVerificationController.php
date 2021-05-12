<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Auth\UserEmailVerification;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
    use ApiResponse;

    /**
     * Resend Email Token to user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function resendEmailToken(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::firstWhere(['email' => $request->email]);

        if (!$user->email_token) {
            return $this->badRequest('Email has already been verified.');
        }

        Mail::to($user->email)->send(new UserEmailVerification($user));

        return $this->success('Email token sent successfully.');
    }


    /**
     * Verify Email Token.
     *
     * @param string $email_token
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function verifyEmailToken($email_token): JsonResponse
    {
        $user = User::firstWhere(['email_token' => $email_token]);

        if (!$user) {
            return $this->badRequest('Email token has been used.');
        }

        $user->update(['email_token' => null, 'is_active' => 1, 'email_verified_at' => now()]);

        return $this->success('Email verified successfully.', array(new UserResource($user)));
    }
}
