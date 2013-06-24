<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Modal_Checkout_Delivery_Class extends Aitsu_Module_Abstract {

    protected $_allowEdit = false;
    protected $_renderOnlyAllowed = true;

    protected function _init() {

        Aitsu_Registry::setExpireTime(0);
    }

    protected function _main() {

        /* get Data */
        $cart = Moraso_Cart::getInstance();
        $data = $cart->getProperty('delivery');
        
        /* create View */
        $view = $this->_getView();
        $view->data = $data;
        return $view->render('index.phtml');
    }

    protected function _cachingPeriod() {

        return 0;
    }

}