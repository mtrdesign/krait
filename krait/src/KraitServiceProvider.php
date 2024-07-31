<?php

namespace MtrDesign\Krait;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * KraitServiceProvider
 */
class KraitServiceProvider extends ServiceProvider
{
    /**
     * Registers the package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/krait.php', 'krait');
        $this->app->register(TablesProvider::class);
    }

    /**
     * Configures the package resources.
     */
    public function boot(): void
    {
        $this->configPublishing();
        $this->configCommands();
        $this->configRoutes();
        $this->configBladeMacros();
    }

    /**
     * Configures the publishable resources offered by the package.
     */
    protected function configPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/krait.php' => config_path('krait.php'),
            ], 'krait-config');
            $this->publishes([
                __DIR__.'/../stubs/tables-empty-index.stub' => resource_path('js/components/tables/index.js'),
            ], 'krait-js');
            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'krait-migrations');
        }
    }

    /**
     * Registers the package's commands.
     */
    protected function configCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\RefreshCommand::class,
                Console\KraitTableCommand::class,
            ]);
        }
    }

    /**
     * Registers the package routes.
     */
    protected function configRoutes(): void
    {
        if ($this->app instanceof CachesRoutes && $this->app->routesAreCached()) {
            return;
        }

        Route::group([
            'prefix' => config('krait.path', 'krait'),
            'as' => 'krait.',
            'namespace' => 'MtrDesign\Krait\Http\Controllers',
            'middleware' => config('krait.global_middlewares', ['web', 'auth']),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Registers the Blade Macros.
     */
    protected function configBladeMacros(): void
    {
        Blade::directive('krait', function ($expression) {
            $expression = Str::replace('\'', '', $expression);
            $expression = Str::replace('"', '', $expression);
            if ($expression === 'js') {
                return Krait::js();
            } else {
                throw new RuntimeException("Invalid Krait resource $expression.");
            }
        });
    }
}
