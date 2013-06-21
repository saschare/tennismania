<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class IndexController extends Zend_Controller_Action {

    public function init() {

        if ($this->getRequest()->getParam('ajax')) {
            header("Content-type: text/javascript");
            $this->_helper->layout->disableLayout();
        }
    }

    public function indexAction() {

        $this->view->plugins = array();

        $namespaces = Moraso_Plugins::getNamespaces();

        foreach ($namespaces as $namespace) {
            $pluginDir = APPLICATION_LIBPATH . '/' . $namespace . '/Plugin';

            $plugins = Aitsu_Util_Dir::scan($pluginDir, 'Class.php');
            $baseLength = strlen($pluginDir);

            foreach ($plugins as $plugin) {
                $pluginPathInfo = explode('/', substr($plugin, $baseLength + 1));

                if ($pluginPathInfo[1] == 'Dashboard') {
                    include_once ($plugin);

                    $registry = call_user_func(array(
                        $namespace . '_Plugin_' . $pluginPathInfo[0] . '_Dashboard_Controller',
                        'register'
                    ));

                    if ($registry->enabled) {
                        $this->view->plugins[] = array(
                            'namespace' => $namespace,
                            'name' => $pluginPathInfo[0]
                        );
                    }
                }
            }
        }
    }

}