<?php

namespace Weigatherboss\BossLogin;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BossLoginServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(BossLogin $extension)
    {
        if (! BossLogin::boot()) {
            return ;
        }
        
        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'boss_login');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [
                    $assets => public_path('vendor/weigatherboss/boss_login'),
                    __DIR__ . '/../database/migrations' => database_path('migrations'),
                    // __DIR__ . '/../src/Models' => app_path('Models'),
                    __DIR__ . '/../config' => config_path(),
                ],
                'boss_login'
            );
        }
        config(['admin.auth.excepts'=>array_merge(config('admin.auth.excepts'),['auth/boss_login'])]);
        $this->app->booted(function () {
            BossLogin::routes(__DIR__.'/../routes/web.php');
            Route::group([],__DIR__.'/../routes/api.php');
        });
    }
}