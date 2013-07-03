<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Modal_Item_Added_Class extends Moraso_Module_Abstract
{
    protected $_renderOnlyAllowed = true;

    protected function _init()
    {
        $nf = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);
        $idart = $_POST['idart'];

        $idartlang = Moraso_Util::getIdArtLang($idart);

        /* get Data */
        $article = Aitsu_Persistence_Article::factory($idart)->load();

        $articleProperties = Aitsu_Persistence_ArticleProperty::factory($idartlang)->load();

        $articlePropertyCart = (object) $articleProperties->cart;

        $cart = Moraso_Cart::getInstance();

        $data = (object) array(
                    'sku' => $articlePropertyCart->sku->value,
                    'price' => $nf->formatCurrency($articlePropertyCart->price->value, 'EUR'),
                    'qty' => $_POST['qty'],
                    'cartItemQty' => $cart->getArticleQty($idart)
        );

        /* create View */
        $view = $this->_getView();
        $view->qty = $data->qty;
        $view->cartItemQty = $data->cartItemQty;
        $view->pagetitle = $article->pagetitle;
        $view->teasertitle = $article->teasertitle;
        $view->mainimage = Moraso_Html_Helper_Image::getPath($idart, $article->mainimage, 400, 400, 2);
        $view->summary = $article->summary;
        $view->sku = $data->sku;
        $view->price = $data->price;

        return $view->render('index.phtml');
    }

}