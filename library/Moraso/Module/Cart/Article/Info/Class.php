<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Article_Info_Class extends Moraso_Module_Abstract
{
    protected $_allowEdit = false;

    protected function _main()
    {
        $idartlang = Aitsu_Registry::get()->env->idartlang;
        $nf = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);

        /* get Data */
        $article = Aitsu_Persistence_ArticleProperty::factory($idartlang)->load();

        $cart = (object) $article->cart;

        $price = $cart->price->value;
        $tax = $cart->tax_class->value * $price / 100;

        $data = (object) array(
                    'sku' => $cart->sku->value,
                    'price' => $nf->formatCurrency($price, 'EUR'),
                    'tax_class' => $cart->tax_class->value,
                    'tax' => $nf->formatCurrency($tax, 'EUR'),
                    'price_net' => $nf->formatCurrency($price - $tax, 'EUR')
        );

        /* create View */
        $view = $this->_getView();
        $view->cart = $data;
        $view->idartlang = $idartlang;
        return $view->render('index.phtml');
    }

    protected function _cachingPeriod()
    {
        return 0;
    }

}