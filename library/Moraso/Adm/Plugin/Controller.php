<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
abstract class Moraso_Adm_Plugin_Controller extends Zend_Controller_Action {

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {

        $this->setRequest($request)->setResponse($response)->_setInvokeArgs($invokeArgs);
        $this->_helper = new Zend_Controller_Action_HelperBroker($this);

        $this->_preInit();

        $this->_helper->viewRenderer->setNoController();

        $this->init();
    }

    protected function _preInit() {
        
    }

    public static function getPosition($id, $plugin, $type = 'article') {

        if ($type === 'category') {
            return self::getPositionCat($id, $plugin);
        }
        
        if (!isset(Aitsu_Article_Config::factory($id)->plugin->$plugin->$type->position)) {
            return 0;
        }

        $position = Aitsu_Article_Config::factory($id)->plugin->$plugin->$type->position;

        if (!isset($position->ifindex)) {
            return $position->default;
        }

        if (Aitsu_Persistence_Article::factory($id)->isIndex()) {
            return $position->ifindex;
        }

        return $position->default;
    }

    protected static function getPositionCat($idcat, $plugin) {

        $config = Aitsu_Persistence_Category::factory($idcat)->load();

        if (!isset($config->configs->plugin->$plugin->category->position)) {
            return 0;
        }

        return $config->configs->plugin->$plugin->category->position;
    }

    protected static function getDashboardEnabled($plugin) {

        $enabled = Moraso_Config::get('dashboard.plugin.' . $plugin . '.enabled');

        return filter_var($enabled, FILTER_VALIDATE_BOOLEAN);
    }

}