<?php

namespace Red\Privat24\Payment;

use Webkul\Payment\Payment\Payment;

class Privat24 extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'privat24';

    /**
     * PayPal web URL generic getter
     *
     * @param  array  $params
     * @return string
     */
    public function getPrivat24Url()
    {
        return sprintf('https://lmi.paysoft.solutions');
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('privat24.standard.redirect');
    }

    /**
     * Return form field array
     *
     * @return array
     * LMI_MERCHANT_ID
     * LMI_PAYMENT_AMOUNT
     * LMI_PAYMENT_NO
     * LMI_PAYMENT_DESC
     * LMI_PAYMENT_SYSTEM
     * LMI_SIM_MODE
     * LMI_HASH
     */
    public function getFormFields()
    {
        $cart = $this->getCart();

        $fields = [];

        $fields['LMI_MERCHANT_ID']       = env('LMI_MERCHANT_ID', '');
        $fields['LMI_PAYMENT_AMOUNT']    = 1; //$cart->sub_total
        $fields['LMI_PAYMENT_NO']        = $cart->id;
        $fields['LMI_PAYMENT_DESC']      = 'Оплата за заказ';
        $fields['LMI_PAYMENT_SYSTEM']    = 49; //privat24
        $fields['LMI_SIM_MODE']          = 1;
        $fields['LMI_HASH']              = hash('sha256', $fields['LMI_MERCHANT_ID'].$fields['LMI_PAYMENT_NO'].$fields['LMI_PAYMENT_AMOUNT']. env('LMI_HASH', ''));
        $fields['LMI_HASH']              = strtoupper($fields['LMI_HASH']);

        return $fields;

    }

}