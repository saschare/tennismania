<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Config_Ini {

    protected function __construct() {
        
    }

    public static function getInstance($ini = null, $env = null) {

        if (empty($ini)) {
            $ini = Aitsu_Mapping::getIni();
        }

        if (empty($env)) {
            $env = Moraso_Util::getEnv();
        }

        $config = new Zend_Config_Ini('application/configs/config.ini', $env, array(
            'allowModifications' => true
        ));

        if ($ini != 'backend') {
            $client_config = new Zend_Config_Ini('application/configs/' . $ini . '.ini', $env, array(
                'allowModifications' => true
            ));

            $config->merge($client_config);
        }

        return $config;
    }

}