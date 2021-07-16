<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Red\Justin\Models\JustinDepartments;
use Red\NP\Models\NpDepartments;
use Webkul\Sales\Models\OrderAddress;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var  \Webkul\Sales\Contracts\Order  $order
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
        try {
            $cityName = '';
            $warehouseName = '';

            $customerAddress = OrderAddress::where([
                'order_id' => $this->order->id,
                'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING
            ])->first();

            if (!empty($customerAddress)) {
                $warehouseRef = $customerAddress->warehouse_ref;
                $cityRef = $customerAddress->city_ref;

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
        } catch (\Exception $e) {
//            var_dump($e->getMessage());die;
        }
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
                    ->to($this->order->customer_email, $this->order->customer_full_name)
                    ->subject(trans('shop::app.mail.order.subject'))
                    ->view('shop::emails.sales.new-order', ['cityName' => $cityName, 'warehouseName' => $warehouseName]);
    }
}
