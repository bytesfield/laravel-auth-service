<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use App\Mail\Auth\PasswordResetMail;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    use JsonResponse;

    public function resetPassword(Request $request)
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

    private function createResetToken(User $user): PasswordReset
    {
        return PasswordReset::create([
            'token' => Str::random(40),
            'email' => $user->email,
        ]);
    }
}
