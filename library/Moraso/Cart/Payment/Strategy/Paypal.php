<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Payment_Strategy_Paypal implements Moraso_Cart_Payment_Strategy {

    public function getCheckoutUrl() {

        return 'https://www.paypal.com/cgi-bin/websc';
    }

    public function getHiddenFormFields($cart_id) {

        $cartData = Smoco_Cart::getData($cart_id);

        $currency = Moraso_Config::get('smoco.shop.currency');
        $language = Moraso_Config::get('smoco.shop.language');

        $confirmURL = Moraso_Config::get('smoco.shop.checkout.confirmURL');

        $orderDescription = Moraso_Config::get('smoco.shop.checkout.orderDescription');
        $displayText = Moraso_Config::get('smoco.shop.checkout.displayText');

        $business = Moraso_Config::get('smoco.shop.payment.paypal.business');

        $hiddenFormFields = array(
            'cmd' => '_xclick',
            'business' => $business,
            'item_name' => $orderDescription,
            'item_number' => $displayText,
            'amount' => str_replace(',', '.', $cartData['amount']),
            'no_shipping' => '0',
            'no_note' => '1',
            'currency_code' => $currency,
            'lc' => $language,
            'bn' => 'PP-BuyNowBF',
            'return' => $confirmURL,
            'cart_id' => $cart_id
        );

        return $hiddenFormFields;
    }

}