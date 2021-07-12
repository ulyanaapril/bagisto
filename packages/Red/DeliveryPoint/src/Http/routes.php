<?php
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    //Checkout Save Address Form Store
    Route::get('red/deliverypoint/warehouses', 'Red\DeliveryPoint\Http\Controllers\ResourceController@warehouses')->name('red.deliverypoint.warehouses');

    //
    Route::get('admin/deliverypoints', 'Red\DeliveryPoint\Http\Controllers\ResourceController@index')->name('admin.deliverypoint.index');

    Route::get('admin/categories/create', 'Red\DeliveryPoint\Http\Controllers\ResourceController@create')->name('admin.deliverypoint.create');

    Route::post('admin/categories/create', 'Red\DeliveryPoint\Http\Controllers\ResourceController@store')->name('admin.deliverypoint.store');

    Route::put('admin/deliverypoint/edit/{id}', 'Red\DeliveryPoint\Http\Controllers\ResourceController@update')->name('admin.deliverypoint.update');

    Route::get('admin/deliverypoint/edit/{id}', 'Red\DeliveryPoint\Http\Controllers\ResourceController@edit')->name('admin.deliverypoint.edit');

    Route::post('admin/deliverypoint/delete/{id}', 'Red\DeliveryPoint\Http\Controllers\ResourceController@destroy')->name('admin.deliverypoint.delete');

    Route::post('admin/deliverypoint/massdelete', 'Red\DeliveryPoint\Http\Controllers\ResourceController@massDestroy')->name('admin.deliverypoint.massdelete');
});

