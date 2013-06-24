<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class PluginController extends Zend_Controller_Action {

    public function indexAction() {

        $this->_helper->viewRenderer->setNoRender();

        $namespace = ucfirst($this->getRequest()->getParam('namespace'));
        $plugin = ucfirst($this->getRequest()->getParam('plugin'));
        $area = ucfirst($this->getRequest()->getParam('area'));

        $pluginPath = APPLICATION_LIBPATH . '/' . $namespace . '/Plugin/' . $plugin . '/' . ucfirst($area) . '/';

        include_once ($pluginPath . 'Class.php');

        $this->view->setScriptPath(array(
            $pluginPath . 'views/'
        ));

        $this->getRequest()->setControllerName($namespace . '_Plugin_' . $plugin . '_' . $area . '_')->setActionName($this->getRequest()->getParam('paction'))->setDispatched(false);
    }

}