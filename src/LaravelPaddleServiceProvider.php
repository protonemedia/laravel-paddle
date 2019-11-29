<?php

namespace Protonemedia\LaravelPaddle;

use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\MockObject\Api;

class LaravelPaddleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-paddle');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-paddle');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-paddle.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-paddle'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/laravel-paddle'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-paddle'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-paddle');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-paddle', function () {
            return new Api;
        });
    }
}
