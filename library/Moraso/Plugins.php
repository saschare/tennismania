<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Plugins {

    public static function installPrivileges($namespace, $plugin, $type) {

        $pluginInfo = simplexml_load_file(APPLICATION_LIBPATH . '/' . ucfirst($namespace) . '/Plugin/' . ucfirst($plugin) . '/' . ucfirst($type) . '/plugin.xml');

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

    public static function getAllPlugins($area = 'article', $idart = 0) {

        $user = Aitsu_Adm_User::getInstance();
        
        $pluginCollection = array();

        $namespaces = self::getNamespaces();

        foreach ($namespaces as $namespace) {
            $pluginDir = APPLICATION_LIBPATH . '/' . $namespace . '/Plugin';

            $plugins = Aitsu_Util_Dir::scan($pluginDir, 'Class.php');
            $baseLength = strlen($pluginDir);

            foreach ($plugins as $plugin) {
                $pluginPathInfo = explode('/', substr($plugin, $baseLength + 1));
                
                $pluginName = $pluginPathInfo[0];

                if (strtolower($pluginPathInfo[1]) === $area) {
                    if ($user->isAllowed(array('area' => 'plugin.' . strtolower($pluginName) . '.' . $area))) {
                        include_once ($plugin);

                        if ($area === 'article') {
                            $registry = call_user_func(array(
                                $namespace . '_Plugin_' . ucfirst($pluginName) . '_' . ucfirst($area) . '_Controller',
                                'register'
                                    ), $idart);
                        } elseif ($area === 'dashboard') {
                            $registry = call_user_func(array(
                                $namespace . '_Plugin_' . ucfirst($pluginName) . '_' . ucfirst($area) . '_Controller',
                                'register'
                            ));
                        }
                        
                        if ($registry->enabled) {
                            $pluginCollection[] = (object) array(
                                        'namespace' => $namespace,
                                        'name' => $pluginName,
                                        'position' => !empty($registry->position) ? $registry->position : 0
                            );
                        }
                    }
                }
            }
        }

        return $pluginCollection;
    }

}