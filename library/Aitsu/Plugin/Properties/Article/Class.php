<?php

/**
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2010, w3concepts AG
 */
class Aitsu_Plugin_Properties_Article_Controller extends Moraso_Adm_Plugin_Controller {

    const ID = '4ca9a84f-d2c0-4011-8903-177a7f000101';

    public function init() {

        header("Content-type: text/javascript");
        $this->_helper->layout->disableLayout();
    }

    public static function register($idart) {

        return (object) array(
                    'name' => 'properties',
                    'tabname' => Aitsu_Registry :: get()->Zend_Translate->translate('Properties'),
                    'enabled' => self :: getPosition($idart, 'properties'),
                    'position' => self :: getPosition($idart, 'properties'),
                    'id' => self :: ID
        );
    }

    public function indexAction() {

        $id = $this->getRequest()->getParam('idart');

        $classExplode = explode('_', __CLASS__);

        $form = Aitsu_Forms::factory(strtolower($classExplode[2]), APPLICATION_LIBPATH . '/' . $classExplode[0] . '/' . $classExplode[1] . '/' . $classExplode[2] . '/' . $classExplode[3] . '/forms/properties.ini');
        $form->title = Aitsu_Translate :: translate('Properties');
        $form->url = $this->view->url(array('namespace' => 'aitsu', 'plugin' => 'properties', 'area' => 'article', 'paction' => 'index'), 'plugin');

        $data = Aitsu_Persistence_Article :: factory($id)->load();
        $form->setValues($data->toArray());

        if ($this->getRequest()->getParam('loader')) {
            $this->view->form = $form;
            header("Content-type: text/javascript");
            return;
        }

        try {
            if ($form->isValid()) {
                Aitsu_Event :: raise('backend.article.edit.save.start', array(
                    'idart' => $id
                ));

                /*
                 * Persist the data.
                 */
                $data->setValues($form->getValues());
                $data->redirect = 1;
                $data->save();

                $this->_helper->json((object) array(
                            'success' => true,
                            'data' => (object) $data->toArray()
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