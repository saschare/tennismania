<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Config extends Aitsu_Config {

    public static function initConfig($ini = null, $env = null) {

        if (empty($ini)) {
            $ini = Aitsu_Mapping::getIni();
        }

        if (empty($env)) {
            $env = Moraso_Util::getEnv();
        }
        
        Aitsu_Registry::get()->config = Moraso_Config_Ini::getInstance('backend', $env);

        Moraso_Config_Db::setConfigFromDatabase($ini, false, $env);
    }

}