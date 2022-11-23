<?php

namespace App\Http\Requests\v1;

use App\Enums\ExceptionErrorCode;
use App\Http\Requests\BaseRequest;
use App\Models\v1\User;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => ['required','exists:users,email'],
            'password' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $this->email)->first();

                    if (isset($user) && !Hash::check($value, $user->password, [])) {
                        return $fail(ExceptionErrorCode::LOGIN_PASSWORD_INCORRECT->value);
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => ExceptionErrorCode::LOGIN_EMAIL_REQUIRED->value,
            'email.exists' => ExceptionErrorCode::LOGIN_EMAIL_DOES_NOT_EXIST->value,
            'password.required' => ExceptionErrorCode::LOGIN_PASSWORD_REQUIRED->value
        ];
    }
}
