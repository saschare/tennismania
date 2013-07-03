<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Payment_Strategy_Cash implements Moraso_Cart_Payment_Strategy
{
    public function getCheckoutUrl()
    {
        return Moraso_Config::get('sys.webpath') . '?confirmPayment';
    }

    public function getHiddenFormFields()
    {
        $cart = Moraso_Cart::getInstance();

        return array(
            'order_id' => $cart->getOrderId()
        );
    }

    public function doConfirmPayment($data)
    {
        trigger_error('### ^^^ ZAHLUNG PER VORKASSE ^^^ ###');
        trigger_error(print_r($data, true));
        trigger_error('### vvv ZAHLUNG PER VORKASSE vvv ###');
        
        $return = array(
            'status' => 'WAITING'
        );
        
        return $return;
    }

    public function actionAfterConfirm($order_id)
    {
        $successUrl = Moraso_Config::get('moraso.shop.checkout.successURL');

        $location = Moraso_Rewrite_Standard::getInstance()->rewriteOutput('{ref:idart-' . $successUrl . '}');

        header('Location: ' . $location);
    }

}