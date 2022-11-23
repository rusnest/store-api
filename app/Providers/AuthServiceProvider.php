<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensExpireIn(now()->addHours(config('token.tokens_expire_in')));

        VerifyEmail::createUrlUsing(
            function ($notifiable) {
                $verifyUrl = URL::temporarySignedRoute(
                    'verification.verify',
                    Carbon::now()->addMinutes(\Config::get('auth.verification.expire', 60)),
                    [
                        'userId' => $notifiable->getKey(),
                        'hash' => sha1($notifiable->getEmailForVerification()),
                    ]
                );

                return config('url_config.frontend_url') . '/callback/email/verify?verify_url=' . urlencode($verifyUrl);
            }
        );

        Passport::tokensCan([
            'verify-2fa-email-code' => 'Verify 2FA Email Code',
            'request-resource' => 'Request Resource',
        ]);
    }
}
