<?php

namespace App\Exceptions;

use App\Enums\ExceptionErrorCode;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(
            function (NotFoundHttpException $e) {
                return ApiResponse::createNotFoundResponse()
                    ->toApiJson();
            }
        );

        $this->renderable(
            function (MissingScopeException $e) {
                return ApiResponse::createFailedResponse()
                    ->setCode(403)
                    ->setMessage('PERMISSION_DENIED')
                    ->setErrors([
                        [
                            'code' => ExceptionErrorCode::AUTHENTICATION_ACCESS_TOKEN_INVALID_SCOPE->value,
                            'message' => ExceptionErrorCode::AUTHENTICATION_ACCESS_TOKEN_INVALID_SCOPE->name
                        ]
                    ])
                    ->toApiJson();
            }
        );

        $this->renderable(
            function (ValidationException $e) {
                return $e->getResponse();
            }
        );

        $this->renderable(
            function (OAuthServerException $e) {
                throw $e;
            }
        );
    }

    /**
     * Override response of exception
     *
     * @override
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        return config('app.debug') ? [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(fn ($trace) => Arr::except($trace, ['args']))->all(),
        ] : [
            'message' => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
            'status' => 'error',
            'error' => [
                [
                    "code" => ExceptionErrorCode::INTERNAL_SERVER_ERROR->value,
                    "message" => "INTERNAL_SERVER_ERROR"
                ]
            ],
            'data' => null
        ];
    }
}
