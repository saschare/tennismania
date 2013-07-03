<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Payment {

    private $_strategy;

    public function __construct(Moraso_Cart_Payment_Strategy $strategy) {

        $this->_strategy = $strategy;
    }

    public function getHiddenFormFields() {

        return $this->_strategy->getHiddenFormFields();
    }

    public function getCheckoutUrl() {

        return $this->_strategy->getCheckoutUrl();
    }

    public function doConfirmPayment($data = array()) {

        return $this->_strategy->doConfirmPayment($data);
    }
    
    public function actionAfterConfirm($order_id) {
        
        $this->_strategy->actionAfterConfirm($order_id);
    }

}