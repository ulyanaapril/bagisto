<?php

namespace Red\Admin\Listeners;

use Illuminate\Support\Facades\Log;
use Red\Admin\Http\SMSClient;
use Webkul\Sales\Models\OrderAddress;

class Order
{
    public $smsLogin;
    public $smsPassword;
    public $smsKey;
    public $smsFrom;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->smsLogin = env('SMS_LOGIN', '');
        $this->smsPassword = env('SMS_PASSWORD', '');
        $this->smsKey = env('SMS_KEY', '');
        $this->smsFrom = env('SMS_FROM', '');
    }

    /**
     * @param $phone
     * @param $message
     */
    public function sendSMS ($phone, $message) {
        $sms = new SMSClient($this->smsLogin, $this->smsPassword, $this->smsKey);
        $id = $sms->sendSMS($this->smsFrom, $phone, $message);

        if ($sms->hasErrors()) {
            $res = $sms->getErrors();
            Log::error('Red/Admin/Listeners/Order',
                ['message' => $message, 'res' => $res]);
        } else {
            $res = $sms->receiveSMS($id);
        }
    }

    /**
     * Send new order SMS to the customer.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function sendNewOrderSMS($order)
    {
        try {
            $phone = $order->shipping_address->phone;

            $message = 'Vashe zamovlennya № ' . $order->id . ' ' . 'Suma ' . ' ' . $order->grand_total . ' grn. tel. (044) 224-40-00';

            $this->sendSMS($phone, $message);
        } catch (\Exception $e) {
            report($e);
        }

    }

    /**
     * Send new invoice SMS to the customer.
     *
     * @param  \Webkul\Sales\Contracts\Invoice  $invoice
     * @return void
     */
    public function sendNewInvoiceSMS($invoice)
    {
        try {
            $orderId = $invoice->order_id;

            $customerAddress = OrderAddress::where([
                'order_id' => $orderId,
                'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING
            ])->first();

            $phone = $customerAddress->phone;

            $message = 'Vashe zamovlennya № ' . $orderId . ' sformovano '  . 'tel. (044) 224-40-00';

            $this->sendSMS($phone, $message);
        } catch (\Exception $e) {
            report($e);
        }

    }

    /**
     * Send new shipment SMS to the customer.
     *
     * @param  \Webkul\Sales\Contracts\Shipment  $shipment
     * @return void
     */
    public function sendNewShipmentSMS($shipment)
    {
        try {
            $orderId = $shipment->order_id;

            $customerAddress = OrderAddress::where([
                'order_id' => $orderId,
                'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING
            ])->first();

            $phone = $customerAddress->phone;

            $message = 'Vashe zamovlennya № ' . $orderId . ' Vidpravleno ' . ' tel. (044) 224-40-00';

            $this->sendSMS($phone, $message);
        } catch (\Exception $e) {
            report($e);
        }

    }
}
