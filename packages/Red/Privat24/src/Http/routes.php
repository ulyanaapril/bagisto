<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('privat24')->group(function () {
        Route::get('/redirect', 'Red\Privat24\Http\Controllers\StandardController@redirect')->name('privat24.standard.redirect');

        Route::get('/success', 'Red\Privat24\Http\Controllers\StandardController@success')->name('privat24.standard.success');

        Route::get('/cancel', 'Red\Privat24\Http\Controllers\StandardController@cancel')->name('privat24.standard.cancel');
    });
});

Route::get('privat24/standard/ipn', 'Red\Privat24\Http\Controllers\StandardController@ipn')->name('privat24.standard.ipn');