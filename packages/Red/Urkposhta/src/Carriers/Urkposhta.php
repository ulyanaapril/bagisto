<?php

namespace Red\Urkposhta\Carriers;

use Config;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;

class Urkposhta extends AbstractShipping
{
    /**
     * Shipping method code
     *
     * @var string
     */
    protected $code  = 'urkposhta';

    /**
     * Returns rate for shipping method
     *
     * @return CartShippingRate|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $object = new CartShippingRate;

        $object->carrier = 'urkposhta';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'urkposhta';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = 0;
        $object->base_price = 0;

        return $object;
    }
}