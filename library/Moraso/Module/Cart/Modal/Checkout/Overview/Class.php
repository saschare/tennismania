<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Modal_Checkout_Overview_Class extends Aitsu_Module_Abstract {

    protected $_allowEdit = false;
    protected $_renderOnlyAllowed = true;

    protected function _init() {

        Aitsu_Registry::setExpireTime(0);
    }

    protected function _main() {

        /* get Data */
        $cart = Moraso_Cart::getInstance();
        $properties = $cart->getProperties();
        $articles = $cart->getArticles();
        
        $cart->createOrder();

        $paymentStrategy = Moraso_Cart::getPaymentStrategy(null, $properties['payment']['method']);
        
        $payment = new Moraso_Cart_Payment($paymentStrategy);

        $checkoutUrl = $payment->getCheckoutUrl();
        $hiddenFields = $payment->getHiddenFormFields();    
        
        /* create View */
        $view = $this->_getView();
        $view->checkoutUrl = $checkoutUrl;
        $view->hiddenFields = $hiddenFields;
        $view->properties = $properties;
        $view->articles = $articles;
        echo $view->render('index.phtml');
    }

    protected function _cachingPeriod() {

        return 0;
    }

}