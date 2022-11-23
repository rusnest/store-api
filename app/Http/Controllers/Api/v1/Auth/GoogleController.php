<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\SocialiteService;
use App\Models\v1\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return SocialiteService::redirect('google');   
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();

        if (isset($user)) {
            return SocialiteService::loginUser($user);
        } else {
            return SocialiteService::registerUser($googleUser);
        }
    }
}
