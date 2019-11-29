<?php

namespace ProtoneMedia\LaravelPaddle;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProtoneMedia\LaravelPaddle\Api\Api;
use ProtoneMedia\LaravelPaddle\Http\WebhookController;

class LaravelPaddleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-paddle.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-paddle');

        $this->app->singleton('laravel-paddle', function () {
            return new Api;
        });

        Route::post(config('laravel-paddle.webhook_uri'), WebhookController::class);
    }
}
