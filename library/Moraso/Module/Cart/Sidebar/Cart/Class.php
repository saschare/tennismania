<?php

/**
 * @author Christian Kehres <c.kehres@wellbo.de>
 * @copyright (c) 2013, wellbo <http://www.wellbo.de>
 */
class Skin_Module_Cart_Sidebar_Cart_Class extends Aitsu_Module_Abstract {

    protected $_allowEdit = false;
    protected $_renderOnlyAllowed = true;

    protected function _init() {

        /* get Data */
        $cart = Aitsu_Registry::get()->smoco->cart;
        $getArticles = $cart->getArticles();

        $return = array();
        foreach ($getArticles as $idart) {
            if (!isset($return['articles'][$idart])) {
                $return['articles'][$idart]['cnt'] = 1;

                $idartlang = Moraso_Util::getIdArtLang($idart);

                $article = Aitsu_Core_Article::factory($idartlang);

                $return['articles'][$idart]['pagetitle'] = $article->get('artlang/pagetitle');
                $return['articles'][$idart]['summary'] = $article->get('artlang/summary');
            } else {
                $return['articles'][$idart]['cnt'] = $return['articles'][$idart]['cnt'] + 1;
            }
        }

        $return['cnt'] = count((array) $getArticles);

        /* prüfen ob es ein Ajax Request ist, dann geb ich nur daten per json zurück */
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');

            echo json_encode($return);
            exit();
        } else {
            $view = $this->_getView();
            $view->cnt = $return['cnt'];
            $view->articles = $return['articles'];
            return $view->render('index.phtml');
        }
    }

}