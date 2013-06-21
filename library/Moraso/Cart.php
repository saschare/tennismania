<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart {

    private static $_instance = null;
    private $_cart;

    public static function getInstance() {

        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    final private function __construct() {

        $this->_cart = new Zend_Session_Namespace('cart');

        if (!isset($this->_cart->articles)) {
            $this->_cart->articles = array();
        }
    }

    final private function __clone() {
        
    }

    public function addArticle($idart, $qty = 1) {

        $oldQty = 0;

        if (isset($this->_cart->articles[$idart]) && !empty($this->_cart->articles[$idart])) {
            $oldQty = (int) $this->_cart->articles[$idart];
        }

        $newQty = (int) ($oldQty + $qty);
        
        $this->_cart->articles[$idart] = $newQty;

        if ($this->_cart->articles[$idart] === $newQty) {
            return true;
        }

        return false;
    }

    public function removeArticle($idart) {

        unset($this->_cart->articles[$idart]);
    }

    public function getArticles() {

        return (object) $this->_cart->articles;
    }

    public function getArticleQty($idart) {

        return (int) $this->_cart->articles[$idart];
    }

}