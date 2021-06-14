<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('visa')->group(function () {
        Route::get('/redirect', 'Red\Visa\Http\Controllers\StandardController@redirect')->name('visa.standard.redirect');

        Route::get('/success', 'Red\Visa\Http\Controllers\StandardController@success')->name('visa.standard.success');

        Route::get('/cancel', 'Red\Visa\Http\Controllers\StandardController@cancel')->name('visa.standard.cancel');
    });
});

Route::get('visa/standard/ipn', 'Red\Visa\Http\Controllers\StandardController@ipn')->name('visa.standard.ipn');