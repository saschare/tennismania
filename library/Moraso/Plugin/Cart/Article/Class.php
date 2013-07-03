<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Plugin_Cart_Article_Controller extends Moraso_Adm_Plugin_Controller
{
    const ID = '51d413d2-d9b0-4818-851e-065cc0a8b230';

    public function init()
    {

        header("Content-type: text/javascript");
        $this->_helper->layout->disableLayout();
    }

    public static function register($idart)
    {
        return (object) array(
                    'name' => 'cart',
                    'tabname' => Aitsu_Registry::get()->Zend_Translate->translate('Cart'),
                    'enabled' => self::getPosition($idart, 'cart'),
                    'position' => self::getPosition($idart, 'cart'),
                    'id' => self::ID
        );
    }

    public function indexAction()
    {
        $idart = $this->getRequest()->getParam('idart');
        $idlang = Aitsu_Registry::get()->session->currentLanguage;

        $classExplode = explode('_', __CLASS__);

        $form = Aitsu_Forms::factory(strtolower($classExplode[2]), APPLICATION_LIBPATH . '/' . $classExplode[0] . '/' . $classExplode[1] . '/' . $classExplode[2] . '/' . $classExplode[3] . '/forms/cart.ini');
        $form->title = Aitsu_Translate::translate('Lucene');
        $form->url = $this->view->url(array('namespace' => 'moraso', 'plugin' => 'cart', 'area' => 'article', 'paction' => 'index'), 'plugin');

        /* set Options */
        $tax_classes = array('19 %' => 19, '7 %' => 7);
        $options = array();
        foreach ($tax_classes as $key => $value) {
            $options[] = (object) array(
                        'name' => $key,
                        'value' => $value
            );
        }
        $form->setOptions('tax_class', $options);

        /* set Values */
        $article = Aitsu_Persistence_ArticleProperty::factory(Moraso_Util::getIdArtLang($idart, $idlang))->load();

        $cart = (object) $article->cart;

        $data = array(
            'idart' => $idart,
            'sku' => $cart->sku->value,
            'price' => $cart->price->value,
            'tax_class' => $cart->tax_class->value
        );

        if (!empty($data)) {

            if (empty($data['sku'])) {
                $data['sku'] = 'SKU' . (10000 + $idart);
            }

            $form->setValues($data);
        }

        if ($this->getRequest()->getParam('loader')) {
            $this->view->form = $form;
            header("Content-type: text/javascript");
            return;
        }

        try {
            if ($form->isValid()) {
                $data = $form->getValues();

                $article->setValue('cart', 'sku', $data['sku']);
                $article->setValue('cart', 'price', $data['price'], 'float');
                $article->setValue('cart', 'tax_class', $data['tax_class']);
                $article->save();

                $this->_helper->json((object) array(
                            'success' => true,
                            'data' => (object) $data
                ));
            } else {
                $this->_helper->json((object) array(
                            'success' => false,
                            'errors' => $form->getErrors()
                ));
            }
        } catch (Exception $e) {
            $this->_helper->json((object) array(
                        'success' => false,
                        'exception' => true,
                        'message' => $e->getMessage()
            ));
        }
    }

}