<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
interface Moraso_Cart_Payment_Strategy {

    public function getCheckoutUrl();

    public function getHiddenFormFields();

    public function doConfirmPayment($data);
    
    public function actionAfterConfirm($order_id);
}