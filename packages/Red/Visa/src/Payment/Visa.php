<?php

namespace Red\Visa\Payment;

use Webkul\Payment\Payment\Payment;

class Visa extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'visa';

    public function getRedirectUrl()
    {
        return route('visa.standard.redirect');
    }

    /**
     * PayPal web URL generic getter
     *
     * @param  array  $params
     * @return string
     */
    public function getVisaUrl()
    {
        return sprintf('https://lmi.paysoft.solutions');
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

        $fields['LMI_MERCHANT_ID']       = 2285;//2285 - main //7394 - test
        $fields['LMI_PAYMENT_AMOUNT']    = 1; //$cart->sub_total
        $fields['LMI_PAYMENT_NO']        = $cart->id;
        $fields['LMI_PAYMENT_DESC']      = 'Оплата за заказ';
        $fields['LMI_PAYMENT_SYSTEM']    = 21; //Visa/MasterCard
        $fields['LMI_SIM_MODE']          = 1;
        $fields['LMI_HASH']              = hash('sha256', $fields['LMI_MERCHANT_ID'].$fields['LMI_PAYMENT_NO'].$fields['LMI_PAYMENT_AMOUNT'].'joiedevivre');//joiedevivre //bagisto
        $fields['LMI_HASH']              = strtoupper($fields['LMI_HASH']);

        return $fields;

    }

}