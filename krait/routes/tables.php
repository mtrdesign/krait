<?php

use Illuminate\Support\Facades\Route;

foreach (app('tables') as $name => $table) {
    $tableName = app($table['table'])->name();
    Route::get($tableName, $table['controller'])->name($tableName);
}
