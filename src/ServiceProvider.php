<?php

namespace Axn\Crudivor;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('crudivor', function($app) {
            return new Repository($app['router']);
        });

        $this->app->alias('crudivor', 'Axn\Crudivor\Repository');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'crudivor');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'crudivor');

        $this->publishes([
            __DIR__.'/../resources/views/' => base_path('resources/views/vendor/crudivor'),
            __DIR__.'/../resources/lang/fr/' => base_path('resources/lang/packages/fr/crudivor'),
            __DIR__.'/../samples/' => app_path(),
        ]);

        if (file_exists($crudivorFile = app_path('crudivor.php'))) {
            require $crudivorFile;
        }

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/routes.php';
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['crudivor'];
    }
}
