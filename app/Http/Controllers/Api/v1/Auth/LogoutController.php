<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\RefreshTokenRepository;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
        $token->delete();
        auth()->guard('web')->logout();
        return ApiResponse::createSuccessResponse()->toApiJson();
    }
}
