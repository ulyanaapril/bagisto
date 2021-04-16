<?php

Route::group(['prefix' => 'api-1c'], function ($router) {

    Route::group(['namespace' => 'Red\API\Http\Controllers', 'middleware' => ['locale', 'theme', 'currency']], function ($router) {
        //Customer routes
        Route::post('customer/login', 'SessionController@create');

        Route::get('customer/logout', 'SessionController@destroy');

        //Order routes
        Route::get('orders', 'ResourceController@index')->defaults('_config', [
            'repository' => 'Webkul\Sales\Repositories\OrderRepository',
            'resource' => 'Webkul\API\Http\Resources\Sales\Order',
            'authorization_required' => true
        ]);

        Route::get('orders/{id}', 'ResourceController@get')->defaults('_config', [
            'repository' => 'Webkul\Sales\Repositories\OrderRepository',
            'resource' => 'Webkul\API\Http\Resources\Sales\Order',
            'authorization_required' => true
        ]);

        Route::post('products/create', 'ResourceController@store')->defaults('_config', [
            'repository' => 'Webkul\Product\Repositories\ProductRepository',
            'authorization_required' => true
        ]);

    });
});