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

        $cart_id = 17;
        $payment_type = 'paypal';
        
        switch ($payment_type) {
            case 'paypal':
                $paymentStrategy = new Smoco_Cart_Payment_Strategy_Paypal();
                break;
            case 'creditcard':
                $paymentStrategy = new Smoco_Cart_Payment_Strategy_Wirecard();
                break;
            case 'postfinance':
                $paymentStrategy = new Smoco_Cart_Payment_Strategy_Datatrans();
                break;
            default:
                $paymentStrategy = new Smoco_Cart_Payment_Strategy_Cash();
        }

        $payment = new Moraso_Cart_Payment($paymentStrategy);

        $nextStep = $payment->getCheckoutUrl();
        $hiddenFields = $payment->getHiddenFormFields($cart_id);

        /* create View */
        $view = $this->_getView();
        $view->nextStep = $nextStep;
        $view->hiddenFields = $hiddenFields;

        echo $view->render('index.phtml');
    }

    protected function _cachingPeriod() {

        return 0;
    }

}