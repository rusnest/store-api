<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RefreshTokenRequest;
use App\Http\Services\TokenService;
use Symfony\Component\HttpFoundation\JsonResponse;

class RefreshTokenController extends Controller
{
    public function refreshToken(RefreshTokenRequest $request)
    {
        $responseToken = TokenService::refreshAccessToken($request->refresh_token);

        if ($responseToken instanceof JsonResponse) {
            return $responseToken;
        }

        return ApiResponse::createSuccessResponse()
            ->setData(
                [
                    'token_type' => $responseToken->token_type,
                    'access_token' => $responseToken->access_token,
                    'refresh_token' => $responseToken->refresh_token,
                    'expires_in' => $responseToken->expires_in
                ]
            )
            ->toApiJson();
    }
}
