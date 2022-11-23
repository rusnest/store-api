<?php

namespace App\Http\Services;

use Exception;
use App\Helpers\ApiResponse;
use App\Enums\ExceptionErrorCode;

class TokenService
{
    public static function grantPasswordToken(
        string $email,
        string $password,
        string $scope = 'request-resource'
    ) {
        $params = [
            'grant_type' => 'password',
            'username' => $email,
            'password' => $password,
        ];
        
        return self::makePostRequest($params, $scope);
    }

    public static function grantIdToken(
        string $id,
        string $scope = 'request-resource'
    ) {
        $params = [
            'grant_type' => 'id_grant',
            'id' => $id,
        ];

        return self::makePostRequest($params, $scope);
    }

    public static function refreshAccessToken(
        string $refreshToken,
        string $scope = 'request-resource'
    ) {
        $params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        return self::makePostRequest($params, $scope);
    }

    protected static function makePostRequest(array $params, string $scope)
    {
        try {
            $params = array_merge(
                [
                    'client_id' => config('services.passport.password_client_id'),
                    'client_secret' => config('services.passport.password_client_secret'),
                    'scope' => $scope,
                ],
                $params
            );

            $proxy = \Request::create('oauth/token', 'post', $params);
            $response = json_decode(app()->handle($proxy)->getContent());
            return $response;
        } catch (Exception $e) {
            return ApiResponse::createAuthenticationFailResponse()
                ->setErrors(
                    [
                        [
                            'code' => ExceptionErrorCode::AUTHENTICATION_REFRESH_TOKEN_INVALID->value,
                            'message' => ExceptionErrorCode::AUTHENTICATION_REFRESH_TOKEN_INVALID->name
                        ]
                    ]
                )
                ->toApiJson();
        }
    }
}
