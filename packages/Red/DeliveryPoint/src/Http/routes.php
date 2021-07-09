<?php
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    //Checkout Save Address Form Store
    Route::get('red/deliverypoint/warehouses', 'Red\DeliveryPoint\Http\Controllers\ResourceController@warehouses')->name('red.deliverypoint.warehouses');
});

