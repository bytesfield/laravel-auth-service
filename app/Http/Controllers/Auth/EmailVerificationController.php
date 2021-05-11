<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Auth\UserEmailVerification;
use App\Http\Resources\Auth\UserResource;

class EmailVerificationController extends Controller
{
    use JsonResponse;

    public function resendEmailToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        if (!$user = User::firstWhere(['email' => $request->email])) {
            return $this->notFound('Email does not exist.');
        }
        if (!$user->email_token) {
            return $this->badRequest('Email has already been verified.');
        }

        Mail::to($user->email)->send(new UserEmailVerification($user));

        return $this->success('Email token sent successfully.');
    }

    public function verifyEmailToken($email_token)
    {

        if (!$user = User::firstWhere(['email_token' => $email_token])) {
            return $this->badRequest('Email token has been used.');
        }

        $user->update(['email_token' => null, 'is_active' => 1, 'email_verified_at' => now()]);

        return $this->success('Email verified successfully.', array(new UserResource($user)));
    }
}
