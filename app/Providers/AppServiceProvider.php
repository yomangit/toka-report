<?php

namespace App\Providers;


use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.env') === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }
        $this->app->bind('path.public', function () {
            return realpath(base_path() . '/../public_html');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return 'https://tokasafe.archimining.com/reset-password/' . $token;
        });
        Event::listen(Logout::class, function ($event) {
            if ($event->user instanceof User) {
                $event->user->update([
                    'onesignal_player_id' => null,
                ]);
            }
        });
    }
}
