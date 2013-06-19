<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Plugins {

    public static function installPrivileges($namespace, $plugin, $type) {

        $pluginInfo = simplexml_load_file(APPLICATION_LIBPATH . '/' . $namespace . '/Plugin/' . $plugin . '/' . $type . '/plugin.xml');

        foreach ($pluginInfo->privileges->privilege as $privilege) {
            $privilegeid = Moraso_Db::fetchOne('' .
                            'SELECT ' .
                            '   privilegeid ' .
                            'FROM ' .
                            '   _acl_privilege ' .
                            'WHERE ' .
                            '   identifier =:privilege', array(
                        ':privilege' => (string) $privilege->identifier
            ));

            if (empty($privilegeid)) {
                $privilegeid = Moraso_Db::put('_acl_privilege', 'privilegeid', array(
                            'identifier' => (string) $privilege->identifier
                ));

                Moraso_Db::put('_acl_privileges', null, array(
                    'roleid' => 18,
                    'privilegeid' => $privilegeid
                ));
            }
        }
    }

    public static function installDatabase($namespace, $plugin, $type) {
        
    }

    public static function getNamespaces() {

        $plugins = Aitsu_Util_Dir::scan(APPLICATION_LIBPATH, 'plugin.xml');
        $baseLength = strlen(APPLICATION_LIBPATH);

        $namespaces = array();
        foreach ($plugins as $plugin) {
            $pluginPathInfo = explode('/', substr($plugin, $baseLength + 1));

            $namespaces[] = $pluginPathInfo[0];
        }

        return array_unique($namespaces);
    }

}