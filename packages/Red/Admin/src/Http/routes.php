<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix(config('app.admin_url'))->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            Route::get('import', 'Red\Admin\Http\Controllers\ImportController@import');

        });
    });
});
