<?php

namespace App\Http\Requests\v1;

use App\Enums\ExceptionErrorCode;
use App\Http\Requests\BaseRequest;
use App\Models\v1\User;

class ResendEmailVerifyRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'exists:users,email',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)->first();
                    if (isset($user) && $user->hasVerifiedEmail()) {
                        $fail(ExceptionErrorCode::RESEND_EMAIL_VERIFY_EMAIL_VERIFIED->value);
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => ExceptionErrorCode::RESEND_EMAIL_VERIFY_EMAIL_REQUIRED->value,
            'email.exists' => ExceptionErrorCode::RESEND_EMAIL_VERIFY_EMAIL_DOES_NOT_EXIST->value
        ];
    }
}
