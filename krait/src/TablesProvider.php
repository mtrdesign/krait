<?php

namespace MtrDesign\Krait;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MtrDesign\Krait\Services\PreviewConfigService;
use MtrDesign\Krait\Services\TablesOrchestrator\TablesOrchestrator;

/**
 * KraitServiceProvider
 */
class TablesProvider extends ServiceProvider
{
    /**
     * @var \SplFileInfo[]
     */
    protected array $tables = [];

    /**
     * Registers the package services.
     */
    public function register(): void
    {
        $this->app->singleton(PreviewConfigService::class, PreviewConfigService::class);
        $this->app->singleton(TablesOrchestrator::class, function ($app) {
            $previewConfigService = $app->make(PreviewConfigService::class);

            return new TablesOrchestrator($previewConfigService);
        });

        foreach (TablesOrchestrator::getTablesDirectoryIterator() as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $this->tables[] = $file;
            $tableClass = TablesOrchestrator::getTableDefinitionClass($file->getPathname());
            $this->app->singleton($tableClass, function ($app) use ($tableClass) {
                $tablesOrchestrator = $app->make(TablesOrchestrator::class);

                return $tablesOrchestrator->getTable($tableClass);
            });
        }
    }

    /**
     * Configures the package resources.
     */
    public function boot(): void
    {
        $tablesOrchestrator = $this->app->make(TablesOrchestrator::class);
        foreach ($this->tables as $table) {
            $tablesOrchestrator->registerTable($table);
        }

        Route::group([
            'prefix' => config('krait.tables_path', 'tables'),
            'namespace' => 'App\Http\Controllers',
            'middleware' => config('krait.global_middlewares', ['web', 'auth']),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/tables.php');
        });
    }
}
