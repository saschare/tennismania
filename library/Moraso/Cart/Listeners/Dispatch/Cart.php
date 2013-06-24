<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Listeners_Dispatch_Cart implements Aitsu_Event_Listener_Interface {

    public static function notify(Aitsu_Event_Abstract $event) {

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if (isset($_POST['action'])) {

                $cart = Moraso_Cart::getInstance();

                if ($_POST['action'] === 'addArticleToCart') {
                    if (!$cart->addArticle($_POST['idart'], $_POST['qty'])) {
                        echo 'da ist was schief gelaufen!';
                        header('X-Error-Message: Artikel konnte dem Warenkorb nicht hinzugefügt werden', true, 500);
                        exit();
                    }
                } elseif ($_POST['action'] === 'addFormData') {
                    $data = array();
                    parse_str($_POST['data'], $data);

                    $formDataType = $data['form_data_type'];

                    unset($data['form_data_type']);

                    $cart->setProperty($formDataType, $data);
                    exit();
                } elseif ($_POST['action'] === 'doCheckout') {
                    header('Content-Type: application/json');
                    echo $cart->doCheckout();
                    exit();
                }
            }
        }

        if (isset($_GET['confirmPayment'])) {
            if (isset($_POST['order_id'])) {
                $order_id = $_POST['order_id'];

                $payment_method = Moraso_Db::fetchOne('SELECT payment_method FROM _cart_order WHERE order_id =:order_id', array(
                            ':order_id' => $order_id
                ));

                switch ($payment_method) {
                    case 'paypal':
                        $paymentStrategy = new Moraso_Cart_Payment_Strategy_Paypal();
                        break;
                    case 'creditcard':
                        $paymentStrategy = new Moraso_Cart_Payment_Strategy_Wirecard();
                        break;
                    case 'postfinance':
                        $paymentStrategy = new Moraso_Cart_Payment_Strategy_Datatrans();
                        break;
                    default:
                        $paymentStrategy = new Moraso_Cart_Payment_Strategy_Cash();
                }
                
                Moraso_Cart::setPaymentStatus($order_id, $paymentStrategy->doConfirmPayment($_POST));
                
                $paymentStrategy->actionAfterConfirm();
            }
        }
    }

}