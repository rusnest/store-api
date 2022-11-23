<?php

namespace App\Http\Middleware;

use App\Enums\ExceptionErrorCode;
use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);
                $token = $this->auth->guard($guard)->user()->token();
            }
        }

        if (!empty($token) && time() > strtotime($token->expires_at)) {
            return ApiResponse::createAuthenticationFailResponse()
                ->setErrors([
                    [
                        'code' => ExceptionErrorCode::AUTHENTICATION_ACCESS_TOKEN_EXPIRED->value,
                        'message' => ExceptionErrorCode::AUTHENTICATION_ACCESS_TOKEN_EXPIRED->name
                    ]
                ])
                ->toApiJson();
        } else if (empty($token)) {
            return ApiResponse::createAuthenticationFailResponse()
                ->setErrors([
                    [
                        'code' => ExceptionErrorCode::AUTHENTICATION_ACCESS_TOKEN_INVALID->value,
                        'message' => ExceptionErrorCode::AUTHENTICATION_ACCESS_TOKEN_INVALID->name
                    ]
                ])
                ->toApiJson();
        }

        return $next($request);
    }
}
