<?php
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::get('red/deliverypoint/warehouses', 'Red\DeliveryPoint\Http\Controllers\ResourceController@warehouses')->name('red.deliverypoint.warehouses');
    //OnePage urls
    Route::post('red/checkout/deliverypoint/save-address', 'Red\DeliveryPoint\Http\Controllers\OnepageController@saveAddress')->name('red.checkout.deliverypoint.save-address');

    Route::post('red/checkout/deliverypoint/save-shipping', 'Red\DeliveryPoint\Http\Controllers\OnepageController@saveShipping')->name('red.checkout.deliverypoint.save-shipping');

    Route::post('red/checkout/deliverypoint/save-order', 'Red\DeliveryPoint\Http\Controllers\OnepageController@saveOrder')->name('red.checkout.deliverypoint.save-order');
});

