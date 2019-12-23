<?php

namespace Creativecurtis\Laramyob;

use Illuminate\Support\ServiceProvider;
use Creativecurtis\Laramyob\Models\Remote\Contact\Customer;

class LaramyobServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laramyob');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laramyob');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->publishes([
            __DIR__.'/../database/migrations/create_myob_configurations_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_myob_configurations_table.php'),
        ], 'migrations');
        
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laramyob.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laramyob'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laramyob'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laramyob'),
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
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laramyob');

        // Register the main class to use with the facade
        $this->app->singleton('laramyob', function () {
            return new Laramyob;
        });
    }
}
