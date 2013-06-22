<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Modal_Checkout_Class extends Aitsu_Module_Abstract {

    protected $_allowEdit = false;
    protected $_renderOnlyAllowed = true;

    protected function _init() {

        Aitsu_Registry::setExpireTime(0);
    }

    protected function _main() {

        $nf = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);

        /* get Data */
        $cart = Moraso_Cart::getInstance();

        $cartArticles = $cart->getArticles();

        $articles = array();
        foreach ($cartArticles as $idart => $qty) {
            $articleInfo = Aitsu_Persistence_Article::factory($idart)->load();

            $price = 17.95;

            $articles[$idart] = (object) array(
                        'qty' => $qty,
                        'pagetitle' => $articleInfo->pagetitle,
                        'price_total' => $nf->formatCurrency(bcmul($price, $qty, 2), 'EUR')
            );
        }

        /* create View */
        $view = $this->_getView();
        $view->articles = (object) $articles;
        echo $view->render('index.phtml');
    }

    protected function _cachingPeriod() {

        return 0;
    }

}