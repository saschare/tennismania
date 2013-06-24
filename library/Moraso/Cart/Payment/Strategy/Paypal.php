<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Payment_Strategy_Paypal implements Moraso_Cart_Payment_Strategy {

    public function getCheckoutUrl() {

        return 'https://www.paypal.com/cgi-bin/websc';
    }

    public function getHiddenFormFields() {
        
        $cart = Moraso_Cart::getInstance();

        $currency = Moraso_Config::get('moraso.shop.currency');
        $language = Moraso_Config::get('moraso.shop.language');

        $confirmURL = Moraso_Config::get('moraso.shop.checkout.confirmURL');

        $orderDescription = Moraso_Config::get('moraso.shop.checkout.orderDescription');
        $displayText = Moraso_Config::get('moraso.shop.checkout.displayText');

        $business = Moraso_Config::get('moraso.shop.payment.paypal.business');

        $hiddenFormFields = array(
            'cmd' => '_xclick',
            'business' => $business,
            'item_name' => $orderDescription,
            'item_number' => $displayText,
            'amount' => str_replace(',', '.', $cart->getAmount()),
            'no_shipping' => '0',
            'no_note' => '1',
            'currency_code' => $currency,
            'lc' => $language,
            'bn' => 'PP-BuyNowBF',
            'return' => $confirmURL,
            'order_id' => ''
        );

        return $hiddenFormFields;
    }

}