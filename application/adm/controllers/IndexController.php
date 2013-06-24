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

        $plugins = Moraso_Plugins::getAllPlugins('dashboard');
        
        foreach ($plugins as $plugin) {            
            $this->view->plugins[] = array(
                'namespace' => $plugin->namespace,
                'name' => $plugin->name
            );
        }
    }

}