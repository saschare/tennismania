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

        return Moraso_Config::get('sys.webpath') . $this->_strategy->getCheckoutUrl();
    }

    public function doConfirmPayment() {

        return $this->_strategy->doConfirmPayment();
    }
    
    public function actionAfterConfirm() {
        
        $this->_strategy->actionAfterConfirm();
    }

}