<?php

namespace App\Http\Middleware;

use App\Enums\ExceptionErrorCode;
use App\Helpers\ApiResponse;
use App\Models\v1\User;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;

class EmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where(['email' => $request->email])->first();
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return ApiResponse::createFailedResponse()
                ->setErrors([
                    [
                        'code' => ExceptionErrorCode::EMAIL_NOT_VERIFIED->value,
                        'messenger' => ExceptionErrorCode::EMAIL_NOT_VERIFIED->name
                    ]
                ])
                ->setMessage('EMAIL_NOT_VERIFIED')
                ->setCode(403)
                ->toApiJson();
        }
        return $next($request);
    }
}
