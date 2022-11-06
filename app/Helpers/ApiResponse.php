<?php

namespace App\Helpers;

use App\Enums\ExceptionErrorCode;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse
{
    private $status;
    private $message;
    private $errors;
    private $data;
    private $code;

    public static function createSuccessResponse()
    {
        $new = new self;

        $new->status = "success";
        $new->message = "SUCCESS";
        $new->code = 200;
        $new->error = null;

        return $new;
    }

    public static function createFailResponse()
    {
        $new = new self;

        $new->status = "fail";
        $new->code = 400;

        return $new;
    }

    public static function createValidationFailResponse()
    {
        $new = new self;

        $new->status = "fail";
        $new->message = "VALIDATION_FAILED";
        $new->code = 422;

        return $new;
    }

    public static function createAuthenticationFailResponse()
    {
        $new = new self;

        $new->status = 'fail';
        $new->message = 'AUTHENTICATION_FAILED';
        $new->errors = [[
            'code' => ExceptionErrorCode::AUTHENTICATION_FAILED->value,
            'message' => ExceptionErrorCode::AUTHENTICATION_FAILED->name
        ]];
        $new->code = 401;

        return $new;
    }

    public static function createNotFoundResponse()
    {
        $new = new self;

        $new->status = 'fail';
        $new->code = 404;
        $new->message = 'API_ENDPOINT_NOT_FOUND';
        $new->errors = [[
            'code' => ExceptionErrorCode::API_ENDPOINT_NOT_FOUND->value,
            'message' => ExceptionErrorCode::API_ENDPOINT_NOT_FOUND->name
        ]];

        return $new;
    }

    public static function createServerErrorResponse()
    {
        $new = new self;
        $new->status = "error";
        $new->code = 500;
        $new->error = [[
            'message' => ExceptionErrorCode::INTERNAL_SERVER_ERROR->name,
            'code' => ExceptionErrorCode::INTERNAL_SERVER_ERROR->value,
        ]];
        $new->message = 'INTERNAL_SERVER_ERROR';
        return $new;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setMessage($message = null)
    {
        $this->message = $message;
        return $this;
    }

    public function setErrors(array $error = null)
    {
        $this->errors = array($error);
        return $this;
    }

    public function setData(array $data = null)
    {
        $this->data = $data;
        return $this;
    }

    public function setCode($code = 200)
    {
        $this->code = $code;
        return $this;
    }

    public function toApiJson()
    {
        return new JsonResponse(
            [
                'status' => $this->status,
                'message' =>  $this->message,
                'error' =>  $this->errors,
                'data' =>  $this->data
            ], $this->code
        );
    }
}
