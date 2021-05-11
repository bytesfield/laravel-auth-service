<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication Route.
// Route::post('forgot-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.forgot');
// Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
// Route::post('register', [RegisterController::class, 'store'])->name('register');
// Route::post('refresh-token', [RefreshTokenController::class, 'refresh'])->name('refresh-token')->middleware('jwt.verify');
//Route::post('login', [LoginController::class, 'authenticate'])->name('login');
// Route::get('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
// Route::post('resend-verification-mail', [EmailVerificationController::class, 'resendEmailToken'])->name('resend-verification-email');
// Route::post('verify-email', [EmailVerificationController::class, 'verifyEmailToken'])->name('verify-email');
