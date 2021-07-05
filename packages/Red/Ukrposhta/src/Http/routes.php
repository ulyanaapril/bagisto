<?php
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    //Checkout Save Address Form Store
    Route::post('red/checkout/ukrposhta/save-address', 'Red\Ukrposhta\Http\Controllers\OnepageController@saveAddress')->name('red.checkout.ukrposhta.save-address');
    Route::post('red/checkout/ukrposhta/save-shipping', 'Red\Ukrposhta\Http\Controllers\OnepageController@saveShipping')->name('red.checkout.ukrposhta.save-shipping');
    Route::post('red/checkout/ukrposhta/save-order', 'Red\Ukrposhta\Http\Controllers\OnepageController@saveOrder')->name('red.checkout.ukrposhta.save-order');
    Route::get('red/ukrposhta/cities', 'Red\Ukrposhta\Http\Controllers\ResourceController@cities')->name('red.ukrposhta.cities');
    Route::get('red/ukrposhta/warehouses', 'Red\Ukrposhta\Http\Controllers\ResourceController@warehouses')->name('red.ukrposhta.warehouses');
    Route::post('red/ukrposhta/create-ttn/{orderId}', 'Red\Ukrposhta\Http\Controllers\ResourceController@createTtn')->name('red.ukrposhta.create-ttn');
    Route::get('red/ukrposhta/print-ttn/', 'Red\Ukrposhta\Http\Controllers\ResourceController@printTtn')->name('red.ukrposhta.print-ttn');
});

