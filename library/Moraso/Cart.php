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

        if (!isset($this->_cart->properties)) {
            $this->_cart->properties = array();
        }
    }

    final private function __clone() {
        
    }

    public function addArticle($id, $qty = 1) {

        $oldQty = 0;

        if (isset($this->_cart->articles[$id]) && !empty($this->_cart->articles[$id])) {
            $oldQty = (int) $this->_cart->articles[$id];
        }

        $newQty = (int) ($oldQty + $qty);

        $this->_cart->articles[$id] = $newQty;

        if ($this->_cart->articles[$id] === $newQty) {
            return true;
        }

        return false;
    }

    public function removeArticle($id) {

        unset($this->_cart->articles[$id]);
    }

    public function getArticles() {

        return (object) $this->_cart->articles;
    }

    public function getArticleQty($id) {

        return (int) $this->_cart->articles[$id];
    }

    public function setProperty($key, $value) {

        $this->_cart->properties[$key] = $value;
    }

    public function getProperty($key) {

        return $this->_cart->properties[$key];
    }

    public function getProperties() {

        return $this->_cart->properties;
    }

    public function getAmount($id = null) {

        if (is_null($id)) {
            $articles = $this->getArticles();

            foreach ($articles as $id => $qty) {
                $price = 17.95;

                $amount = $amount + bcmul($price, $qty, 2);
            }
        } else {
            $price = 129.95;
            $qty = 3;
            $amount = bcmul($price, $qty, 2);
        }


        return $amount;
    }

    public function doCheckout() {

        $order_id = Moraso_Db::put('_cart_order', 'order_id', array(
                    'timestamp' => 'CURRENT_TIMESTAMP'
        ));

        $articles = $this->getArticles();

        foreach ($articles as $id => $qty) {
            $id = Moraso_Db::put('_cart_order_has_article', 'id', array(
                        'order_id' => $order_id,
                        'article_id' => $id,
                        'qty' => $qty
            ));
        }

        return json_encode(array(
            'order_id' => $order_id
        ));
    }

    public static function setPaymentStatus($order_id, $payed) {

        Moraso_Db::put('_cart_order', 'order_id', array(
            'order_id' => $order_id,
            'payed' => $payed
        ));
    }

}