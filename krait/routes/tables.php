<?php

use Illuminate\Support\Facades\Route;

$tablesOrchestrator = app(\MtrDesign\Krait\TablesOrchestrator::class);

foreach ($tablesOrchestrator->getTables() as $table) {
    $instance = $table->getInstance();

    $action = str_replace("App\\Http\\Controllers", '', $table->getController());

    Route::middleware($instance->middlewares())
        ->get($table->getRoute(), $table->getController());
}
