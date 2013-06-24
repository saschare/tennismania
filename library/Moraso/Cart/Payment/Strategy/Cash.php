<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Payment_Strategy_Cash implements Moraso_Cart_Payment_Strategy {

    public function getCheckoutUrl() {

        return 'de/?confirmPayment';
    }

    public function getHiddenFormFields() {

        return array(
            'order_id' => ''
        );
    }

    public function doConfirmPayment() {

        return true;
    }

    public function actionAfterConfirm() {

        $successUrl = Moraso_Config::get('moraso.shop.checkout.successURL');

        $location = Moraso_Rewrite_Standard::getInstance()->rewriteOutput('{ref:idart-' . $successUrl . '}');
        
        trigger_error('neue Location:{ref:idart-' . $successUrl . '}');

        header('Location: ' . $location);
    }

}