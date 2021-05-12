<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Mail\Auth\PasswordResetMail;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    use ApiResponse;

    /**
     * Reset Password.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        if (!$user = User::firstWhere(['email' => $request->email])) {
            return $this->notFound('Email does not exist.');
        }

        $reset = $this->createResetToken($user);

        Mail::to($request->email)->send(new PasswordResetMail($user, $reset));

        return $this->success('Reset password link has been sent to your email.');
    }

    /**
     * Reset Password.
     *
     * @param \App\Models\User $user
     *
     * @return \App\Models\PasswordReset
     */
    private function createResetToken(User $user): PasswordReset
    {
        return PasswordReset::create([
            'token' => Str::random(40),
            'email' => $user->email,
        ]);
    }
}
