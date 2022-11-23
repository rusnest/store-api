<?php

namespace App\Http\Requests\v1;

use App\Enums\ExceptionErrorCode;
use App\Http\Requests\BaseRequest;

class RefreshTokenRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'refresh_token' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'refresh_token.required' => ExceptionErrorCode::REFRESH_TOKEN_REQUIRED->value
        ];
    }
}
