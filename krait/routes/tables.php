<?php

use Illuminate\Support\Facades\Route;

$tablesOrchestrator = app(\MtrDesign\Krait\Services\TablesOrchestrator\TablesOrchestrator::class);

foreach ($tablesOrchestrator->getTables() as $table) {
    $instance = $table->getInstance();

    $action = str_replace('App\\Http\\Controllers\\', '', $table->getController()->namespace);

    Route::middleware($instance->middlewares())
        ->get($table->getRoute(), $action);
}
