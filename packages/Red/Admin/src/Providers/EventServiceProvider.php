<?php

namespace Red\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('checkout.order.save.after', 'Red\Admin\Listeners\Order@sendNewOrderSMS');

        Event::listen('sales.invoice.save.after', 'Red\Admin\Listeners\Order@sendNewInvoiceSMS');

        Event::listen('sales.shipment.save.after', 'Red\Admin\Listeners\Order@sendNewShipmentSMS');

    }
}