<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Plugin_Placeholder_Generic_Controller extends Aitsu_Adm_Plugin_Controller {

    public function init() {
        $this->_helper->layout->disableLayout();
    }

    public function indexAction() {
        header("Content-type: text/javascript");
    }

    public function storeAction() {

        $data = Aitsu_Db::fetchAll('' .
                        'SELECT ' .
                        '   placeholder.id, ' .
                        '   placeholder.identifier, ' .
                        '   value.value ' .
                        'FROM ' .
                        '   _placeholder AS placeholder ' .
                        'LEFT JOIN ' .
                        '   _placeholder_values AS value ON ' .
                        '   ( ' .
                        '       value.placeholderid = placeholder.id ' .
                        '   AND ' .
                        '       value.idlang =:idlang ' .
                        '   )', array(
                    ':idlang' => Aitsu_Registry::get()->session->currentLanguage
        ));

        $this->_helper->json((object) array(
                    'data' => $data
        ));
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');

        $this->_helper->layout->disableLayout();
        
        $classExplode = explode('_', __CLASS__);

        $form = Aitsu_Forms::factory(strtolower($classExplode[2]), APPLICATION_LIBPATH . '/' . $classExplode[0] . '/' . $classExplode[1] . '/' . $classExplode[2] . '/' . $classExplode[3] . '/forms/edit.ini');
        $form->title = Aitsu_Translate :: translate('Edit placeholder');
        $form->url = $this->view->url(array('paction' => 'edit'), 'gplugin');

        $data['id'] = $id;
        $data['identifier'] = Aitsu_Placeholder::get($id);
        $data['value'] = Aitsu_Placeholder::get($data['identifier']);

        $form->setValues($data);

        if ($this->getRequest()->getParam('loader')) {
            $this->view->form = $form;
            header("Content-type: text/javascript");
            return;
        }

        try {
            if ($form->isValid()) {

                $data = $form->getValues();

                Aitsu_Placeholder::set($data['identifier'], $data['value'], $data['id']);

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

    public function deleteAction() {

        $id = $this->getRequest()->getParam('id');

        $this->_helper->layout->disableLayout();

        Aitsu_Db::query("DELETE FROM `_placeholder` WHERE `id` =:id", array(':id' => $id));

        $this->_helper->json((object) array(
                    'success' => true,
        ));
    }

}