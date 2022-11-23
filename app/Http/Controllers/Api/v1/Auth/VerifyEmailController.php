<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ResendEmailVerifyRequest;
use App\Models\v1\User;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify($userId, Request $request)
    {
        $verified = false;
        $user = User::findOrFail($userId);
        if ($request->hasValidSignature() && !$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $verified = true;
        }
        
        return ApiResponse::createSuccessResponse()
            ->setData(['verified' => $verified])
            ->toApiJson();
    }

    public function resendEmailVerify(ResendEmailVerifyRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->sendEmailVerificationNotification();

        return ApiResponse::createSuccessResponse()->toApiJson();
    }
}
