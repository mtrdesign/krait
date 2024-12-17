<?php

namespace MtrDesign\Krait;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MtrDesign\Krait\Services\PreviewConfigService;
use MtrDesign\Krait\Services\TablesOrchestrator\TablesOrchestrator;
use SplFileInfo;

/**
 * TablesProvider
 *
 * Handles the App Tables Registration functionalities.
 */
class TablesProvider extends ServiceProvider
{
    /**
     * The app table definition files
     *
     * @var SplFileInfo[]
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

        $iterator = TablesOrchestrator::getTablesDirectoryIterator();
        if ($iterator) {
            foreach (TablesOrchestrator::getTablesDirectoryIterator() as $file) {
                if (! $file->isFile()) {
                    continue;
                }
                $this->tables[] = $file;
                $this->registerTable($file);
            }
        }
    }

    /**
     * Registers a single table to the container
     */
    private function registerTable(SplFileInfo $file): void
    {
        $tableClass = TablesOrchestrator::getTableDefinitionClass($file->getPathname());
        $this->app->singleton($tableClass, function ($app) use ($tableClass) {
            $tablesOrchestrator = $app->make(TablesOrchestrator::class);

            return $tablesOrchestrator->getTable($tableClass);
        });
    }

    /**
     * Configures the package resources.
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        // Registering the tables to the orchestrator
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
