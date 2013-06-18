<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Plugin_Configuration_Generic_Controller extends Aitsu_Adm_Plugin_Controller {

    public function init() {
        $this->_helper->layout->disableLayout();
    }

    public function indexAction() {
        header("Content-type: text/javascript");
    }

    public function storeAction() {

        $data = Moraso_Db::fetchAll('' .
                        'select ' .
                        '   id, ' .
                        '   config, ' .
                        '   env, ' .
                        '   identifier, ' .
                        '   value ' .
                        'from ' .
                        '   _moraso_config ' .
                        'order by ' .
                        '   find_in_set(env, "default,live,prod,staging,preprod,dev"), ' .
                        '   identifier asc');

        $this->_helper->json((object) array(
                    'data' => $data
        ));
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');

        $this->_helper->layout->disableLayout();

        $classExplode = explode('_', __CLASS__);

        $form = Aitsu_Forms::factory(strtolower($classExplode[2]), APPLICATION_LIBPATH . '/' . $classExplode[0] . '/' . $classExplode[1] . '/' . $classExplode[2] . '/' . $classExplode[3] . '/forms/config.ini');
        $form->title = Aitsu_Translate::translate('Edit Configuration');
        $form->url = $this->view->url(array('paction' => 'edit'), 'gplugin');

        /* mapping */
        $configs = array();
        $match = array();

        foreach (Aitsu_Util_Dir :: scan(APPLICATION_PATH . '/configs/clients', '*.ini') as $config) {
            preg_match('@/([^/]+)\\.ini$@m', $config, $match);
            $configs[] = (object) array(
                        'value' => $match[1],
                        'name' => $match[1]
            );
        }

        $form->setOptions('config', $configs);

        /* environment */
        $environments = array('default', 'live', 'prod', 'staging', 'preprod', 'dev');

        $envs = array();
        foreach ($environments as $env) {
            $envs[] = (object) array(
                        'value' => $env,
                        'name' => $env
            );
        }
        $form->setOptions('env', $envs);

        $data = Moraso_Db::fetchRow('' .
                        'select ' .
                        '   id, ' .
                        '   config, ' .
                        '   env, ' .
                        '   identifier, ' .
                        '   value ' .
                        'from ' .
                        '   _moraso_config ' .
                        'where ' .
                        '   id =:id', array(
                    ':id' => $id
        ));

        $form->setValues($data);

        if ($this->getRequest()->getParam('loader')) {
            $this->view->form = $form;
            header("Content-type: text/javascript");
            return;
        }

        try {
            if ($form->isValid()) {

                $data = $form->getValues();

                if (empty($data['id'])) {
                    unset($data['id']);
                }
                Moraso_Db::put('_moraso_config', 'id', $data);

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

        $this->_helper->layout->disableLayout();

        Moraso_Db::query('' .
                'delete from ' .
                '   _moraso_config ' .
                'where ' .
                '   id =:id', array(
            ':id' => $this->getRequest()->getParam('id')
        ));

        $this->_helper->json((object) array(
                    'success' => true,
        ));
    }

}