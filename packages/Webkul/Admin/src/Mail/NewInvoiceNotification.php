<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Red\Justin\Models\JustinDepartments;
use Red\NP\Models\NpDepartments;
use Webkul\Sales\Models\OrderAddress;

class NewInvoiceNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The invoice instance.
     *
     * @param  \Webkul\Customer\Contracts\Invoice  $invoice
     */
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Customer\Contracts\Invoice  $invoice
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->invoice->order;

        $cityName = '';
        $warehouseName = '';

        try {

            $customerAddress = OrderAddress::where([
                'order_id' => $order->id,
                'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
                'customer_id' => $order->customer_id
            ])->first();

            if (!empty($customerAddress)) {
                $warehouseRef = $customerAddress->warehouse_ref;
                $cityRef = $customerAddress->city_ref;

                if ($order->shipping_method === 'deliverypoint' && !empty($warehouseRef)) {
                    $inventorySource = core()->getCurrentChannel()->inventory_sources()
                        ->where('id', $warehouseRef)
                        ->first();
                    if (!empty($inventorySource)) {
                        $warehouseName = !empty($inventorySource->desscription) ? $inventorySource->description : $inventorySource->name;
                    }
                }

                if (!empty($warehouseRef) && !empty($cityRef)) {
                    if ($order->shipping_method === 'np') {
                        $data = NpDepartments::getOrderShipping($cityRef, $warehouseRef);
                        $cityName = $data['cityName'];
                        $warehouseName = $data['warehouseName'];

                    }
                    if ($order->shipping_method === 'justin') {
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
                    ->to($order->customer_email, $order->customer_full_name)
                    ->subject(trans('shop::app.mail.invoice.subject', ['order_id' => $order->increment_id]))
                    ->view('shop::emails.sales.new-invoice', ['cityName' => $cityName, 'warehouseName' => $warehouseName]);
    }
}
