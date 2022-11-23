<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\LoginRequest;
use App\Http\Services\TokenService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $responseToken = TokenService::grantPasswordToken($request->email, $request->password);
            return ApiResponse::createSuccessResponse()
                ->setData(
                    [
                        'token_type' => $responseToken->token_type,
                        'expires_in' => $responseToken->expires_in,
                        'access_token' => $responseToken->access_token,
                        'refresh_token' => $responseToken->refresh_token,
                    ]
                )
                ->toApiJson();
        }
    }
}
