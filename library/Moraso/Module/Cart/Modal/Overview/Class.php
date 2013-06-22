<?php

/**
 * @author Christian Kehres <c.kehres@wellbo.de>
 * @copyright (c) 2013, wellbo <http://www.wellbo.de>
 */
class Moraso_Module_Cart_Modal_Overview_Class extends Aitsu_Module_Abstract {

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
            $sku = '28sad74saf';

            $articles[$idart] = (object) array(
                        'qty' => $qty,
                        'pagetitle' => $articleInfo->pagetitle,
                        'summary' => $articleInfo->summary,
                        'price' => $nf->formatCurrency($price, 'EUR'),
                        'sku' => $sku,
                        'mainimage' => Moraso_Html_Helper_Image::getPath($idart, $articleInfo->mainimage, 100, 100, 2),
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