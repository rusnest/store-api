<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\ApiResponse;
use App\Helpers\FileStorage;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RegisterRequest;
use App\Models\v1\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $userRequest = $request->only(['full_name', 'display_name', 'email', 'phone_number']);
        $userRequest['password'] = Hash::make($request->password);

        if ($request->profile_image_file) {
            $userRequest['profile_image'] = FileStorage::storeAvatarFromUpload($request->profile_image_file);
        }

        $user = User::create($userRequest);

        event(new Registered($user));

        return ApiResponse::createSuccessResponse()->toApiJson();
    }
}
