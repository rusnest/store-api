<?php

namespace App\Http\Requests;

use App\Enums\ExceptionErrorCode;
use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $listErrors = [];
        foreach ($validator->errors()->all() as $error) {
            $errorMessage = ExceptionErrorCode::from($error)->name;
            array_push(
                $listErrors, [
                    'message' => $errorMessage,
                    'code' => (int) $error
                ]
            );
        }

        $response = ApiResponse::createValidationFailResponse()
            ->setErrors($listErrors)
            ->toApiJson();

        throw new ValidationException($validator, $response);
    }
}
