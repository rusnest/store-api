<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getProfile()
    {
        return ApiResponse::createSuccessResponse()
            ->setData(
                [
                    'user' => Auth::user()->userInfo()
                ]
            )
            ->toApiJson();
    }
}
