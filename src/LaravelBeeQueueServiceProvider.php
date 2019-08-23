<?php

namespace Stasyanko\LaravelBeeQueue;

use Illuminate\Support\ServiceProvider;
use Predis\Client;

class LaravelBeeQueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function () {
            return new Client();
        });
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel_bee_queue.php',
            'laravel_bee_queue'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel_bee_queue.php' => config_path('laravel_bee_queue.php'),
        ], 'laravel-bee-queue-config');
    }
}
