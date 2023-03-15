<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Message;
use App\Models\Notification;
use App\Policies\GroupPolicy;
use App\Policies\MessagePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Group' => 'App\Policies\GroupPolicy',
        Group::class => GroupPolicy::class,
        Message::class => MessagePolicy::class,
        Notification::class => NotificationPolicy::class,
        Task::class => TaskPolicy::class,
        TaskList::class => TaskListPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return $this->getResetPasswordLink($user->email, $token);
        });
    }

    private function getResetPasswordLink(string $email, string $token) {
        $appUrl = config('url', 'questifyer.com');
        $appEnv = config('env', 'production');
        $query = "/reset-password?token={$token}&email={$email}";
        if ($appEnv === 'production')
            return "https://{$appUrl}{$query}";
        else if ($appEnv === 'local')
            return "{$appUrl}:8000{$query}";
        else return "http://{$appUrl}{$query}";
    }
}
