<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Skin_Module_Cart_Checkout_Class extends Moraso_Module_Abstract {

    protected $_allowEdit = false;

    protected function _main() {

        $nextStep = Moraso_Config::get('smoco.shop.checkout.payment.idart');
        
        /* create View */
        $view = $this->_getView();
        $view->nextStep = Moraso_Rewrite_Standard::getInstance()->rewriteOutput('{ref:idart-' . $nextStep . '}');
        return $view->render('index.phtml');
    }

    protected function _cachingPeriod() {

        return 0;
    }

}