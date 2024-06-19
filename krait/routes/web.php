<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api',
    'as' => 'krait.'
], function () {
    Route::group([
        'prefix' => 'tables/{table}',
        'as' => 'tables.'
    ], function() {
        Route::get('preview-configuration', 'Api\\PreviewConfigurationSearchController');
    });


    Route::group([
        'prefix' => 'preview-configuration',
        'as' => 'preview-configuration.'
    ], function() {
//        For future versions with caching...
//        Route::get('{route}/{table}', function () {
//            return 'the table config';
//        });

        Route::group([
            'prefix' => '{record}/columns',
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

