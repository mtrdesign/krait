<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api',
    'as' => 'krait.'
], function () {
    Route::group([
        'prefix' => 'preview-configuration/{table}',
        'as' => 'preview-configuration.'
    ], function() {
        Route::group([
            'prefix' => 'columns',
            'as' => 'columns.'
        ], function() {
            Route::put(
                'reorder',
                'Api\\ColumnsReorderController'
            )->name('reorder');

            Route::put(
                'resize',
                'Api\\ColumnsResizeController'
            )->name('resize');

            Route::put('visibility', 'Api\\ColumnsHideController')
                ->name('hide');

            Route::put('sort', 'Api\\ColumnsSortController')
                ->name('sort');
        });
    });
});

