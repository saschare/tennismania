<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Payment_Strategy_Paypal implements Moraso_Cart_Payment_Strategy
{
    public function getCheckoutUrl()
    {
        return 'https://www.paypal.com/cgi-bin/websc';
    }

    public function getHiddenFormFields()
    {
        $cart = Moraso_Cart::getInstance();

        $hiddenFormFields = array(
            'cmd' => '_xclick',
            'business' => Moraso_Config::get('moraso.shop.payment.paypal.business'),
            'item_name' => sprintf(Moraso_Config::get('moraso.shop.checkout.orderDescription'), (int) $cart->getOrderId()),
            'item_number' => sprintf(Moraso_Config::get('moraso.shop.checkout.displayText'), (int) $cart->getOrderId()),
            'amount' => str_replace(',', '.', $cart->getAmount()),
            'no_shipping' => '0',
            'no_note' => '1',
            'currency_code' => Moraso_Config::get('moraso.shop.currency'),
            'lc' => Moraso_Config::get('moraso.shop.language'),
            'bn' => 'PP-BuyNowBF',
            'return' => Moraso_Config::get('sys.webpath') . 'de/?confirmPayment',
            'order_id' => $cart->getOrderId()
        );

        return $hiddenFormFields;
    }

    public function doConfirmPayment($data)
    {
        trigger_error('### ^^^ ZAHLUNG PER PAYPAL ^^^ ###');
        trigger_error(print_r($data, true));
        trigger_error('### vvv ZAHLUNG PER PAYPAL vvv ###');
        
        $return = array(
            'status' => 'SUCCESS'
        );
        
        return $return;
    }

    public function actionAfterConfirm($order_id)
    {
        $payed = Moraso_Cart::getPaymentStatus($order_id);

        if ($payed) {
            $url = Moraso_Config::get('moraso.shop.checkout.successURL');
        } else {
            $url = Moraso_Config::get('moraso.shop.checkout.errorURL');
        }

        $location = Moraso_Rewrite_Standard::getInstance()->rewriteOutput('{ref:idart-' . $url . '}');

        header('Location: ' . $location);
    }

}