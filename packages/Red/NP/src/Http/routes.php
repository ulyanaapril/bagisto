<?php
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    //Checkout Save Address Form Store
    Route::post('red/checkout/save-address', 'Red\NP\Http\Controllers\OnepageController@saveAddress')->name('red.checkout.save-address');
    Route::post('red/checkout/save-shipping', 'Red\NP\Http\Controllers\OnepageController@saveShipping')->name('red.checkout.save-shipping');
    Route::post('red/checkout/save-order', 'Red\NP\Http\Controllers\OnepageController@saveOrder')->name('red.checkout.save-order');
    Route::get('red/np/cities', 'Red\NP\Http\Controllers\ResourceController@cities')->name('red.np.cities');
    Route::get('red/np/warehouses', 'Red\NP\Http\Controllers\ResourceController@warehouses')->name('red.np.warehouses');
});

