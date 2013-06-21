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

    public function getHiddenFormFields($cart_id) {

        return $this->_strategy->getHiddenFormFields($cart_id);
    }

    public function getCheckoutUrl() {

        return $this->_strategy->getCheckoutUrl();
    }

}