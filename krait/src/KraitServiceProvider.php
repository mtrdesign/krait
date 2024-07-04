<?php

namespace MtrDesign\Krait;

use FilesystemIterator;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use MtrDesign\Krait\Services\PreviewConfigService;
use MtrDesign\Krait\Tables\BaseTable;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
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
        $this->app->singleton('test', function () {
            return 'asd';
        });
        $this->mergeConfigFrom(__DIR__.'/../config/krait.php', 'krait');

        $this->registerTables();
        $this->registerServices();
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
            ]);
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
                Console\CreateTableClassCommand::class,
                Console\CreateTableComponentCommand::class,
                Console\CreateTableControllerCommand::class,
                Console\CreateTableCommand::class,
                Console\RefreshCommand::class,
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
            'middleware' => config('krait.middleware', ['web', 'auth']),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        Route::group([
            'prefix' => config('krait.tables_path', 'tables'),
            'namespace' => 'App\Http\Controllers',
            'middleware' => config('krait.middleware', ['web', 'auth']),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/tables.php');
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

    /**
     * Registers all tables in the Container.
     */
    protected function registerTables(): void
    {
        $tablesDirectory = config('krait.tables_directory');
        Krait::sanityPath($tablesDirectory);

        $tables = [];
        if (file_exists(config('krait.tables_directory'))) {
            $tablesDirectory = config('krait.tables_directory');
            Krait::sanityPath($tablesDirectory);

            foreach (new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($tablesDirectory,
                    FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST) as $file) {

                if (! $file->isFile()) {
                    continue;
                }

                $namespace = Str::replace(app_path().'/', 'App/', $tablesDirectory);
                $namespace = Str::replace('/', '\\', $namespace);

                $className = explode($tablesDirectory.'/', $file->getPathname())[1];
                $className = Str::replace('/', '\\', $className);
                $className = substr($className, 0, -4);

                $fullClass = sprintf('%s\%s', $namespace, $className);

                if (! is_subclass_of($fullClass, BaseTable::class)) {
                    continue;
                }

                $this->app->singleton($fullClass, function ($app) use ($fullClass) {
                    $previewConfigService = $app->make(PreviewConfigService::class);
                    $table = new $fullClass($previewConfigService);
                    $table->initColumns();

                    return $table;
                });

                $tables[$className] = [
                    'table' => $fullClass,
                    'controller' => 'App\\Http\\Controllers\\Tables\\'.$className.'Controller',
                ];
            }
        }

        $this->app->singleton('tables', function () use ($tables) {
            return $tables;
        });
    }

    /**
     * Registers the package services.
     */
    protected function registerServices(): void
    {
        $this->app->singleton(PreviewConfigService::class, PreviewConfigService::class);
    }
}
