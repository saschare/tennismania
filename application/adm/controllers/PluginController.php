<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class PluginController extends Zend_Controller_Action {

    public function indexAction() {

        $plugin = $this->getRequest()->getParam('plugin');
        $paction = $this->getRequest()->getParam('paction');
        $area = $this->getRequest()->getParam('area');

        if (!Aitsu_Adm_User :: getInstance()->isAllowed(array('area' => 'plugin.' . $plugin . '.' . $area))) {
            return;
        }

        $this->_helper->viewRenderer->setNoRender(true);

        $pluginPath = APPLICATION_LIBPATH . '/Moraso/Plugin/' . ucfirst($plugin) . '/' . ucfirst($area) . '/';

        include_once ($pluginPath . 'Class.php');

        $this->view->setScriptPath(array(
            $pluginPath . 'views/'
        ));

        $this->getRequest()->setControllerName('Moraso_Plugin_' . ucfirst($plugin) . '_' . ucfirst($area) . '_')->setActionName($paction)->setDispatched(false);
    }

    public function articleAction() {

        $plugin = $this->getRequest()->getParam('plugin');

        if (is_object($plugin)) {
            $plugin = $plugin->name;
        }

        $action = $this->getRequest()->getParam('paction');
        $controller = $plugin . 'Article';
        $this->_helper->viewRenderer->setNoRender(true);

        include_once (APPLICATION_PATH . '/plugins/article/' . $plugin . '/Class.php');

        $this->view->setScriptPath(array(
            APPLICATION_PATH . '/plugins/article/' . $plugin . '/views/'
        ));

        $this->getRequest()->setControllerName($controller)->setActionName($action)->setDispatched(false);
    }

    public function categoryAction() {

        $plugin = $this->getRequest()->getParam('plugin');

        if (is_object($plugin)) {
            $plugin = $plugin->name;
        }

        $action = $this->getRequest()->getParam('paction');
        $controller = $plugin . 'Category';
        $this->_helper->viewRenderer->setNoRender(true);

        include_once (APPLICATION_PATH . '/plugins/category/' . $plugin . '/Class.php');

        $this->view->setScriptPath(array(
            APPLICATION_PATH . '/plugins/category/' . $plugin . '/views/'
        ));

        $this->getRequest()->setControllerName($controller)->setActionName($action)->setDispatched(false);
    }

}