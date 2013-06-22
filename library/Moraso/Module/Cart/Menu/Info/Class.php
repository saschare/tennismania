<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Menu_Info_Class extends Aitsu_Module_Abstract {

    protected $_allowEdit = false;
    protected $_renderOnlyAllowed = true;

    protected function _init() {

        Aitsu_Registry::setExpireTime(0);
    }

    protected function _main() {

        /* prüfen ob es sich um einen Ajax Request handelt */
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');

            $return = array();
            $return['success'] = false;

            $nf = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);

            /* get Data */
            $cart = Moraso_Cart::getInstance();

            $cartArticles = $cart->getArticles();
            foreach ($cartArticles as $idart => $qty) {
                $price = 17.95;

                $return['qty'] = $return['qty'] + $qty;
                $return['amount'] = $return['amount'] + bcmul($price, $qty, 2);

                $return['success'] = true;
            }

            $return['amount'] = $nf->formatCurrency($return['amount'], 'EUR');

            echo json_encode($return);
        }
    }

    protected function _cachingPeriod() {

        return 0;
    }

}