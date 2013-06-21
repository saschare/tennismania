<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class PluginController extends Zend_Controller_Action {

    public function indexAction() {

        $this->_helper->viewRenderer->setNoRender(true);
        
        $plugin = $this->getRequest()->getParam('plugin');
        $area = $this->getRequest()->getParam('area');

        
        
        if (!Aitsu_Adm_User::getInstance()->isAllowed(array('area' => 'plugin.' . strtolower($plugin) . '.' . strtolower($area)))) {
            return;
        }

        $namespace = $this->getRequest()->getParam('namespace');

        $pluginPath = APPLICATION_LIBPATH . '/' . ucfirst($namespace) . '/Plugin/' . ucfirst($plugin) . '/' . ucfirst($area) . '/';

        include_once ($pluginPath . 'Class.php');

        $this->view->setScriptPath(array(
            $pluginPath . 'views/'
        ));

        $this->getRequest()->setControllerName($namespace . '_Plugin_' . ucfirst($plugin) . '_' . ucfirst($area) . '_')->setActionName($this->getRequest()->getParam('paction'))->setDispatched(false);
    }

}