<?php

namespace Red\API\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Api service provider
 *
 * @author    Jane Doe <janedoe@gmail.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(){
        include __DIR__ . '/../Http/routes.php';
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
