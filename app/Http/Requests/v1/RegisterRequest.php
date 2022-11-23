<?php

namespace App\Http\Requests\v1;

use App\Enums\ExceptionErrorCode;
use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'full_name' => ['required'],
            'display_name' => ['required'],
            'phone_number' => ['required'],
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'confirmed']
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => ExceptionErrorCode::REGISTER_FULLNAME_REQUIRED->value,
            'display_name.required' => ExceptionErrorCode::REGISTER_DISPLAYNAME_REQUIRED->value,
            'email.required' => ExceptionErrorCode::REGISTER_EMAIL_REQUIRED->value,
            'email.unique' => ExceptionErrorCode::REGISTER_EMAIL_EXISTED->value,
            'password.required' => ExceptionErrorCode::REGISTER_PASSWORD_REQUIRED->value,
            'password.confirmed' => ExceptionErrorCode::REGISTER_PASSWORD_NOT_CONFIRMED->value,
            'phone_number.required' => ExceptionErrorCode::REGISTER_PHONE_NUMBER_REQUIRED->value
        ];
    }
}
