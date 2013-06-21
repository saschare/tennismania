<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Payment_Strategy_Cash implements Moraso_Cart_Payment_Strategy {

    public function getCheckoutUrl() {

        return Moraso_Config::get('smoco.shop.checkout.done');
    }

    public function getHiddenFormFields($cart_id) {

        $cartData = Smoco_Cart::getData($cart_id);

        $hiddenFormFields = array(
            'amount' => $cartData['amount'],
            'cart_id' => $cart_id
        );

        return $hiddenFormFields;
    }

}