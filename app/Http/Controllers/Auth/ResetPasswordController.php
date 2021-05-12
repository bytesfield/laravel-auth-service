<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Models\PasswordReset;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\PasswordResetRequest;

class ResetPasswordController extends Controller
{
    use ApiResponse;

    /**
     * Reset password.
     *
     * @param \App\Http\Requests\Auth\PasswordResetRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {

        $reset = $this->verifyToken($request->token);

        if (!$reset) {
            return $this->badRequest('Invalid token or expired token');
        }

        $user = User::whereEmail($reset->email)->first();

        if (Hash::check($request->password, $user->password)) {
            return $this->badRequest("Sorry you can't use your old password");
        }

        $user->update(['password' => Hash::make($request->password)]);
        PasswordReset::where(['email' => $reset->email])->delete();

        return $this->success('Password reset successfully');
    }

    /**
     * Verify Token
     *
     * @param string $token
     *
     * @return \App\Models\PasswordReset
     */
    private function verifyToken(string $token)
    {
        $reset = PasswordReset::firstWhere(['token' => $token]);

        return ($reset && !$reset->created_at->addHours(PasswordReset::TOKEN_EXPIRES_IN_HOURS)->isPast()) ? $reset : null;
    }
}
