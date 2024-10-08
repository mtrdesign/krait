<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'preview-configurations/{table}',
    'as' => 'preview-configuration.',
], function () {
    Route::group([
        'prefix' => 'columns',
        'as' => 'columns.',
    ], function () {
        Route::post(
            'reorder',
            'Api\\ColumnsReorderController'
        )->name('reorder');

        Route::post(
            'resize',
            'Api\\ColumnsResizeController'
        )->name('resize');

        Route::post('visibility', 'Api\\ColumnsHideController')
            ->name('hide');

        Route::post('sort', 'Api\\ColumnsSortController')
            ->name('sort');

        Route::post('items-per-page', 'Api\\ItemsPerPageSaveController')
            ->name('save-items-per-page');
    });
});

Route::group([
    'prefix' => 'table',
    'as' => 'table',
], function () {
    Route::post(
        'structure',
        '\MtrDesign\Krait\Http\Controllers\Api\TableStructureController'
    )->name('structure');
});
