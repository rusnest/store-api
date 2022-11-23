<?php

use App\Http\Controllers\Api\v1\Auth\GoogleController;
use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\LogoutController;
use App\Http\Controllers\Api\v1\Auth\RefreshTokenController;
use App\Http\Controllers\Api\v1\Auth\RegisterController;
use App\Http\Controllers\Api\v1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [RegisterController::class, 'register']);
Route::get('/email/verify-email/{userId}', [VerifyEmailController::class, 'verify'])
    ->name('verification.verify');

Route::post('/email/resend-verify', [VerifyEmailController::class, 'resendEmailVerify']);

Route::post('refresh-token', [RefreshTokenController::class, 'refreshToken']);

Route::middleware(['email_verified'])->group(
    function () {
        Route::post('/login', [LoginController::class, 'login']);
    }
);

Route::controller(GoogleController::class)->group(
    function () {
        Route::get('auth/google', 'redirect');
        Route::get('auth/google/callback', 'callback');
    }
);

Route::middleware(['auth:api', 'scope:request-resource'])->group(
    function () {
        Route::get('user/profile', [UserController::class, 'getProfile']);
        Route::post('logout', [LogoutController::class, 'logout']);
    }
);
