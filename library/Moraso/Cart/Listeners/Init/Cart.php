<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Listeners_Init_Cart implements Aitsu_Event_Listener_Interface {

    public static function notify(Aitsu_Event_Abstract $event) {

        //Aitsu_Registry::get()->cart = Moraso_Cart::getInstance();
        $cart = Moraso_Cart::getInstance();

        /* prüfen ob es sich um einen Ajax Request handelt */
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            /* prüfen ob auch eine action übertragen wurde */
            if (isset($_POST['action'])) {

                /* prüfen ob es sich um die "addArticleToCart" action handelt */
                if ($_POST['action'] === 'addArticleToCart') {

                    /* Artikel dem Warenkorb hinzufügen und prüfen ob das auch geklappt hat */
                    if (!$cart->addArticle($_POST['idart'], $_POST['qty'])) {
                        echo 'da ist was schief gelaufen!';
                        header('X-Error-Message: Artikel konnte dem Warenkorb nicht hinzugefügt werden', true, 500);
                        exit();
                    }
                }
            }
        }
    }

}