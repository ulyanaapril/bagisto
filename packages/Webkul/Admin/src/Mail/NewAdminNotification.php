<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Red\Justin\Models\JustinDepartments;
use Red\NP\Models\NpDepartments;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartAddress;

class NewAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \Webkul\Sales\Contracts\Order
     */
    public $order;

    /**
     * Create a new message instance.
     * 
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $customerAddress = null;
        $cityName = '';
        $warehouseName = '';

        try {
            $cart = Cart::getCart();

            if (!empty($cart)) {
                $customerAddress = CartAddress::where([
                    'cart_id' => $cart->id,
                    'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
                    'customer_id' => $cart->customer_id
                ])->first();

                if (!empty($customerAddress)) {
                    $warehouseRef = $customerAddress->warehouse_ref;
                    $cityRef = $customerAddress->city_ref;

                    if ($this->order->shipping_method === 'deliverypoint' && !empty($warehouseRef)) {
                        $inventorySource = core()->getCurrentChannel()->inventory_sources()
                            ->where('id', $warehouseRef)
                            ->first();
                        if (!empty($inventorySource)) {
                            $warehouseName = !empty($inventorySource->desscription) ? $inventorySource->description : $inventorySource->name;
                        }
                    }

                    if (!empty($warehouseRef) && !empty($cityRef)) {
                        if ($this->order->shipping_method === 'np') {
                            $data = NpDepartments::getOrderShipping($cityRef, $warehouseRef);
                            $cityName = $data['cityName'];
                            $warehouseName = $data['warehouseName'];

                        }
                        if ($this->order->shipping_method === 'justin') {
                            $data = JustinDepartments::getOrderShipping($warehouseRef);
                            $cityName = $data['cityName'];
                            $warehouseName = $data['warehouseName'];
                        }
                    }

                }
            }

        } catch (\Exception $e) {
//            var_dump($e->getMessage());die;
        }
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
                    ->to(core()->getAdminEmailDetails()['email'])
                    ->subject(trans('shop::app.mail.order.subject'))
                    ->view('shop::emails.sales.new-admin-order', ['cityName' => $cityName, 'warehouseName' => $warehouseName, 'customerAddress' => $customerAddress]);
    }
}