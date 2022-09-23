<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
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

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return $this->getResetPasswordLink($user->email, $token);
        });
    }

    private function getResetPasswordLink(string $email, string $token) {
        $appUrl = env('APP_URL');
        $appEnv = env('APP_ENV');
        $query = "/reset-password?token={$token}&email={$email}";
        if ($appEnv === 'production')
            return "https://{$appUrl}{$query}";
        else if ($appEnv === 'local')
            return "{$appUrl}:8000{$query}";
        else return "http://{$appUrl}{$query}";
    }
}
