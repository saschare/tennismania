<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Plugin_Plugins_Generic_Controller extends Moraso_Adm_Plugin_Controller {

    public function init() {
        $this->_helper->layout->disableLayout();
    }

    public function indexAction() {
        header("Content-type: text/javascript");
    }

    public function storeAction() {

        $namespaces = Moraso_Plugins::getNamespaces();

        foreach ($namespaces as $namespace) {
            $pluginDir = APPLICATION_LIBPATH . '/' . $namespace . '/Plugin';

            $pluginCollection = Aitsu_Util_Dir::scan($pluginDir, 'Class.php');

            $baseLength = strlen($pluginDir);

            foreach ($pluginCollection as $plugin) {
                $pluginXml = realpath(dirname($plugin) . '/plugin.xml');
                $pluginInfo = simplexml_load_file($pluginXml);

                $pluginPathInfo = explode('/', substr($plugin, $baseLength + 1));

                $plugins[] = array(
                    'namespace' => $namespace,
                    'dir' => $pluginPathInfo[0],
                    'name' => (string) $pluginInfo->name,
                    'type' => $pluginPathInfo[1],
                    'author' => (string) $pluginInfo->author,
                    'copyright' => (string) $pluginInfo->copyright,
                    'path' => dirname($plugin)
                );
            }
        }

        $this->_helper->json((object) array(
                    'plugins' => $plugins
        ));
    }

    public function installAction() {

        $this->_helper->layout->disableLayout();

        $namespace = $this->getRequest()->getParam('namespace');
        $dir = $this->getRequest()->getParam('dir');
        $type = $this->getRequest()->getParam('type');

        include_once (APPLICATION_LIBPATH . '/' . $namespace . '/Plugin/' . $dir . '/' . $type . '/Class.php');

        call_user_func_array($namespace . '_Plugin_' . $dir . '_' . $type . '_Controller::install', array($namespace, $dir, $type));

        $this->_helper->json((object) array(
                    'success' => true
        ));
    }

}