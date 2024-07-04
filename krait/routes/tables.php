<?php

use Illuminate\Support\Facades\Route;

foreach (app('tables') as $name => $table) {
    $tableObject = app($table['table']);
    $tableName = $tableObject->name();
    Route::middleware(
        array_merge(
            config('krait.global_middlewares', []),
            $tableObject->middlewares()
        )
    )->get($tableName, $table['controller'])->name($tableName);
}
