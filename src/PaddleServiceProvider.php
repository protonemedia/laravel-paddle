<?php

namespace ProtoneMedia\LaravelPaddle;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProtoneMedia\LaravelPaddle\Api\Api;
use ProtoneMedia\LaravelPaddle\Http\WebhookController;

class PaddleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('paddle.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/paddle'),
            ], 'views');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'paddle');

        Blade::directive('paddle', function () {
            return "<?php echo view('paddle::javaScriptSetup'); ?>";
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'paddle');

        $this->app->singleton('laravel-paddle', function () {
            return new Api;
        });

        Route::post(config('paddle.webhook_uri'), WebhookController::class);
    }
}
