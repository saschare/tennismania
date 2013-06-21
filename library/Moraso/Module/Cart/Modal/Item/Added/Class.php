<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Modal_Item_Added_Class extends Moraso_Module_Abstract {

    protected $_renderOnlyAllowed = true;

    protected function _init() {

        /* get Data */
        $article = Aitsu_Persistence_Article::factory($_POST['idart'])->load();

        $cart = Moraso_Cart::getInstance();

        $cartItemQty = $cart->getArticleQty($_POST['idart']);

        $price = 17.95;
        $sku = '28sad74saf';

        $nf = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);

        /* create View */
        $view = $this->_getView();
        $view->qty = $_POST['qty'];
        $view->cartItemQty = $cartItemQty;
        $view->pagetitle = $article->pagetitle;
        $view->teasertitle = $article->teasertitle;
        $view->mainimage = Moraso_Html_Helper_Image::getPath($_POST['idart'], $article->mainimage, 400, 400, 2);
        $view->summary = $article->summary;
        $view->sku = $sku;
        $view->price = $nf->formatCurrency($price, 'EUR');

        return $view->render('index.phtml');
    }

}