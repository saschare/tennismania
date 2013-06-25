<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class PluginController extends Zend_Controller_Action {

    public function indexAction() {

        $request = $this->getRequest();

        $namespace = ucfirst($request->getParam('namespace'));
        $plugin = ucfirst($request->getParam('plugin'));
        $area = ucfirst($request->getParam('area'));
        $paction = $request->getParam('paction');

        $controller = $namespace . '_Plugin_' . $plugin . '_' . $area . '_';

        $pluginPath = APPLICATION_LIBPATH . '/' . $namespace . '/Plugin/' . $plugin . '/' . ucfirst($area) . '/';

        $this->_helper->viewRenderer->setNoRender();

        include_once ($pluginPath . 'Class.php');

        $this->view->setScriptPath(array(
            $pluginPath . 'views/'
        ));

        $request->setControllerName(ucfirst($controller));
        $request->setActionName($paction);
        $request->setDispatched(false);
    }

}