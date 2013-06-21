<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Cart_Payment_Strategy_Wirecard implements Moraso_Cart_Payment_Strategy {

    public function getCheckoutUrl() {

        return 'https://secure.wirecard-cee.com/qpay/init.php';
    }

    public function getHiddenFormFields($cart_id) {

        $cartData = Smoco_Cart::getData($cart_id);

        $currency = Moraso_Config::get('smoco.shop.currency');
        $language = Moraso_Config::get('smoco.shop.language');
        $imageURL = Moraso_Config::get('smoco.shop.imageURL');

        $cancelURL = Moraso_Config::get('smoco.shop.checkout.cancelURL');
        $failureURL = Moraso_Config::get('smoco.shop.checkout.failureURL');
        $pendingURL = Moraso_Config::get('smoco.shop.checkout.pendingURL');
        $successURL = Moraso_Config::get('smoco.shop.checkout.successURL');
        $confirmURL = Moraso_Config::get('smoco.shop.checkout.confirmURL');
        $serviceURL = Moraso_Config::get('smoco.shop.checkout.serviceURL');

        $orderDescription = Moraso_Config::get('smoco.shop.checkout.orderDescription');
        $displayText = Moraso_Config::get('smoco.shop.checkout.displayText');

        $customerId = Moraso_Config::get('smoco.shop.payment.wirecard.customerId');

        $hiddenFormFields = array(
            'customerId' => $customerId,
            'amount' => $cartData['amount'],
            'currency' => $currency,
            'language' => $language,
            'orderDescription' => $orderDescription,
            'successURL' => Moraso_Config::get('sys.webpath') . $successURL,
            'confirmURL' => Moraso_Config::get('sys.webpath') . $confirmURL,
            'cart_id' => $cart_id
        );

        $requestFingerprint = $this->_generateRequestFingerprint($hiddenFormFields);

        $hiddenFormFields['paymenttype'] = 'SELECT';
        $hiddenFormFields['displayText'] = $displayText;
        $hiddenFormFields['cancelURL'] = Moraso_Config::get('sys.webpath') . $cancelURL;
        $hiddenFormFields['failureURL'] = Moraso_Config::get('sys.webpath') . $failureURL;
        $hiddenFormFields['pendingURL'] = Moraso_Config::get('sys.webpath') . $pendingURL;
        $hiddenFormFields['imageURL'] = $imageURL;
        $hiddenFormFields['serviceURL'] = Moraso_Config::get('sys.webpath') . $serviceURL;
        $hiddenFormFields['requestFingerprintOrder'] = $requestFingerprint['requestFingerprintOrder'];
        $hiddenFormFields['requestfingerprint'] = $requestFingerprint['requestFingerprint'];

        return $hiddenFormFields;
    }

    private function _generateRequestFingerprint(array $data) {

        $requestFingerprintOrder = array();
        $requestFingerprintSeed = array();

        $requestFingerprintOrder[] = 'secret';
        $requestFingerprintSeed[] = Moraso_Config::get('smoco.shop.payment.wirecard.secret');

        foreach ($data as $key => $value) {
            $requestFingerprintOrder[] = $key;
            $requestFingerprintSeed[] = $value;
        }

        $requestFingerprintOrder[] = "requestFingerprintOrder";
        $requestFingerprintSeed[] = implode(',', $requestFingerprintOrder);

        return array(
            'requestFingerprintOrder' => implode(',', $requestFingerprintOrder),
            'requestFingerprint' => md5(implode('', $requestFingerprintSeed))
        );
    }

}