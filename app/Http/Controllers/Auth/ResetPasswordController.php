<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\PasswordResetRequest;

class ResetPasswordController extends Controller
{
    use JsonResponse;

    public function resetPassword(PasswordResetRequest $request)
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

    private function verifyToken(string $token)
    {
        $reset = PasswordReset::firstWhere(['token' => $token]);

        return ($reset && !$reset->created_at->addHours(PasswordReset::TOKEN_EXPIRES_IN_HOURS)->isPast()) ? $reset : null;
    }
}
