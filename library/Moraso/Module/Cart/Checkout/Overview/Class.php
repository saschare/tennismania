<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Skin_Module_Cart_Checkout_Overview_Class extends Moraso_Module_Abstract {

    protected $_allowEdit = false;

    protected function _main() {

        $cart_id = 17;

        /* get Payment Data */
        switch ($_POST['payment']) {
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

        $payment = new Smoco_Cart_Payment($paymentStrategy);

        $nextStep = $payment->getCheckoutUrl();
        $hiddenFields = $payment->getHiddenFormFields($cart_id);

        /* create View */
        $view = $this->_getView();
        $view->nextStep = $nextStep;
        $view->hiddenFields = $hiddenFields;
        return $view->render('index.phtml');
    }

    protected function _cachingPeriod() {

        return 0;
    }

}