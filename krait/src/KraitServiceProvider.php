<?php

namespace MtrDesign\Krait;

use DirectoryIterator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use RuntimeException;

class KraitServiceProvider extends ServiceProvider
{
    public function register() {
        $this->app->singleton('test', function() {
            return 'asd';
        });
        $this->mergeConfigFrom(__DIR__.'/../config/krait.php', 'krait');

        $this->registerTables();
    }

    public function boot() {
        $this->configPublishing();
        $this->configCommands();
        $this->configRoutes();
        $this->configBladeMacros();
    }

    /**
     * Configure the publishable resources offered by the package.
     *
     * @return void
     */
    protected function configPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/krait.php' => config_path('krait.php'),
            ], 'krait-config');
            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ]);
        }
    }

    /**
     * Register the package's commands.
     */
    protected function configCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }

    protected function configRoutes(): void
    {
//        if ($this->app instanceof CachesRoutes && $this->app->routesAreCached()) {
//            return;
//        }

        Route::group([
            'prefix' => config('krait.path', 'krait'),
            'namespace' => 'MtrDesign\Krait\Http\Controllers',
//            'middleware' => config('krait.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }


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

    protected function registerTables()
    {
        $directory = new DirectoryIterator(__DIR__ . '/Tables');
        foreach ($directory as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filename = $file->getFilename();
            if (
                !str_ends_with($filename, 'Table.php') ||
                $filename === 'BaseTable.php'
            ) {
                continue;
            }

            $tableClassName = substr($file, 0, -4);
            $tableClass = config('krait.tables_namespace') . $tableClassName;

            $this->app->singleton($tableClass, function () use ($tableClass) {
                $table = new $tableClass();
                $table->initColumns();
                return $table;
            });
        }
    }
}
