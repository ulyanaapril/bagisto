<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        \Webkul\Admin\Http\Controllers\Sales\OrderController::class =>
            \Red\Admin\Http\Controllers\OrderController::class,
    ];
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $this->app->concord->registerModel(
            \Webkul\Sales\Contracts\Order::class, \Red\Admin\Models\Order::class
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
