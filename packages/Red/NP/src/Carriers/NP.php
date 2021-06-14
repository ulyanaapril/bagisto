<?php

namespace Red\NP\Carriers;

use Config;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;

class NP extends AbstractShipping
{
    /**
     * Shipping method code
     *
     * @var string
     */
    protected $code  = 'np';

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

        $object->carrier = 'np';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'np';
        $object->method_title = trans('np::app.new-post');
        $object->method_description = $this->getConfigData('description');
        $object->price = 0;
        $object->base_price = 0;
        $object->is_calculate_tax = false;

        return $object;
    }
}