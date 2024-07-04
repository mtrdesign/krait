<?php

use Illuminate\Support\Facades\Route;

foreach (app('tables') as $name => $table) {
    $tableObject = app($table['table']);
    $tableName = $tableObject->name();
    Route::middleware($tableObject->middlewares())
        ->get($tableName, $table['controller'])
        ->name($tableName);
}
