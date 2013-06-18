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

        $pluginDir = APPLICATION_LIBPATH . '/Moraso/Plugin';

        $pluginCollection = Aitsu_Util_Dir::scan($pluginDir, 'Class.php');

        $baseLength = strlen($pluginDir);

        $plugins = array();
        foreach ($pluginCollection as $plugin) {
            $pluginXml = realpath(dirname($plugin) . '/plugin.xml');
            $pluginInfo = simplexml_load_file($pluginXml);

            $pluginPathInfo = explode('/', substr($plugin, $baseLength + 1));

            $plugins[] = array(
                'id' => (string) $pluginInfo->id,
                'name' => (string) $pluginInfo->name,
                'type' => $pluginPathInfo[1],
                'author' => (string) $pluginInfo->author,
                'copyright' => (string) $pluginInfo->copyright
            );
        }

        $this->_helper->json((object) array(
                    'plugins' => $plugins
        ));
    }

}