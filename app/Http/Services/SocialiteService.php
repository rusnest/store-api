<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
use App\Helpers\FileStorage;
use App\Models\v1\GoogleAccount;
use App\Models\v1\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteService
{
    static function redirect($provider)
    {
        return ApiResponse::createSuccessResponse()
            ->setData([
                'redirectUrl' => Socialite::driver($provider)
                    ->stateless()
                    ->redirect()
                    ->getTargetUrl()
            ])->toApiJson();
    }

    static function loginUser(User $user)
    {
        $responseToken = TokenService::grantIdToken($user->id);
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

    static function registerUser($googleUser)
    {
        GoogleAccount::create(
            [
                'google_id' => $googleUser->id,
                'json' => json_encode($googleUser)
            ]
        );

        if (isset($googleUser->avatar)) {
            $avatarFile = FileStorage::getFileByUrl($googleUser->avatar);
            $avatar = FileStorage::storeAvatarFromSns($avatarFile);
        }

        $newUser = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'google_id' => $googleUser->id,
                'profile_image' => isset($avatar) ? $avatar : null,
                'full_name' => $googleUser->name,
                'display_name' => $googleUser->nickname,
                'email_verified_at' => now()
            ]
        );

        Auth::loginUsingId($newUser->id);
        $responseToken = TokenService::grantIdToken($newUser->id);

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
