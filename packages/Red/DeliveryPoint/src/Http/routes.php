<?php
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    //Checkout Save Address Form Store
    Route::get('red/deliverypoint/warehouses', 'Red\DeliveryPoint\Http\Controllers\ResourceController@warehouses')->name('red.deliverypoint.warehouses');

    //
    Route::get('admin/deliverypoints', 'Red\DeliveryPoint\Http\Controllers\ResourceController@index')->name('admin.deliverypoint.index');

//    Route::get('/categories/create', 'Webkul\Category\Http\Controllers\CategoryController@create')->defaults('_config', [
//        'view' => 'admin::catalog.categories.create',
//    ])->name('admin.catalog.categories.create');
//
//    Route::post('/categories/create', 'Webkul\Category\Http\Controllers\CategoryController@store')->defaults('_config', [
//        'redirect' => 'admin.catalog.categories.index',
//    ])->name('admin.catalog.categories.store');

    Route::put('admin/deliverypoint/edit/{id}', 'Red\DeliveryPoint\Http\Controllers\ResourceController@update')->name('admin.deliverypoint.update');

    Route::get('admin/deliverypoint/edit/{id}', 'Red\DeliveryPoint\Http\Controllers\ResourceController@edit')->name('admin.deliverypoint.edit');

//    Route::post('/categories/delete/{id}', 'Webkul\Category\Http\Controllers\CategoryController@destroy')->name('admin.catalog.categories.delete');
});

