<?php
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    //Checkout Save Address Form Store
    Route::post('red/checkout/justin/save-address', 'Red\Justin\Http\Controllers\OnepageController@saveAddress')->name('red.checkout.justin.save-address');
    Route::post('red/checkout/justin/save-shipping', 'Red\Justin\Http\Controllers\OnepageController@saveShipping')->name('red.checkout.justin.save-shipping');
    Route::post('red/checkout/justin/save-order', 'Red\Justin\Http\Controllers\OnepageController@saveOrder')->name('red.checkout.justin.save-order');
    Route::get('red/justin/cities', 'Red\Justin\Http\Controllers\ResourceController@cities')->name('red.justin.cities');
    Route::get('red/justin/city', 'Red\Justin\Http\Controllers\ResourceController@city')->name('red.justin.city');
    Route::get('red/justin/warehouse', 'Red\Justin\Http\Controllers\ResourceController@warehouse')->name('red.justin.warehouse');
    Route::get('red/justin/warehouses', 'Red\Justin\Http\Controllers\ResourceController@warehouses')->name('red.justin.warehouses');
    Route::post('red/justin/create-ttn/{orderId}', 'Red\Justin\Http\Controllers\ResourceController@createTtn')->name('red.justin.create-ttn');
});

