<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Adm_Controller_Navigation extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $t = Zend_Registry::get('Zend_Translate');
        try {

            $plugins = $this->_getPlugins(null, array('Aitsu', 'Moraso'));

            $pluginsNav = array(
                'label' => 'Plugins',
                'id' => 'plugins',
                'controller' => 'plugins',
                'action' => 'index',
                'route' => 'default',
                'pages' => $plugins,
                'ac' => array(
                    'area' => 'plugins'
                ),
                'icon' => 'tm-plugin'
            );
            
            if (empty($plugins)) {
                $pluginsNav['ac'] = array(
                    'area' => 'keinZutritt'
                );
            }
            
            $nav = array(
                array(
                    'label' => $t->translate('Dashboard'),
                    'id' => 'dashboard',
                    'controller' => 'index',
                    'action' => 'index',
                    'route' => 'default',
                    'icon' => 'tm-dashboard'
                ),
                array(
                    'label' => $t->translate('Content'),
                    'id' => 'page',
                    'controller' => 'data',
                    'action' => 'index',
                    'route' => 'default',
                    'ac' => array(
                        'area' => 'article'
                    ),
                    'icon' => 'tm-page'
                ),
                array(
                    'label' => $t->translate('Configuration'),
                    'id' => 'management',
                    'controller' => 'acl',
                    'action' => 'profil',
                    'route' => 'default',
                    'ac' => array(
                        'area' => 'management'
                    ),
                    'icon' => 'tm-management',
                    'pages' => array(
                        array(
                            'label' => $t->translate('User profile'),
                            'id' => 'management',
                            'controller' => 'acl',
                            'action' => 'profil',
                            'route' => 'default',
                            'ac' => array(
                                'area' => 'userprofile'
                            ),
                            'icon' => 'tm-user-profile'
                        ),
                        array(
                            'label' => $t->translate('User, Roles and Privileges'),
                            'id' => 'acl',
                            'controller' => 'acl',
                            'action' => 'index',
                            'route' => 'default',
                            'ac' => array(
                                'area' => 'usermanagement',
                                'action' => 'crud'
                            ),
                            'icon' => 'tm-usermanagement'
                        ),
                        array(
                            'label' => $t->translate('Clients'),
                            'id' => 'client',
                            'controller' => 'client',
                            'action' => 'index',
                            'route' => 'default',
                            'ac' => array(
                                'area' => 'client'
                            ),
                            'icon' => 'tm-client'
                        ),
                        array(
                            'label' => $t->translate('Mappings'),
                            'id' => 'mapping',
                            'controller' => 'mapping',
                            'action' => 'index',
                            'route' => 'default',
                            'ac' => array(
                                'area' => 'mapping'
                            ),
                            'icon' => 'tm-mapping'
                        ),
                        array(
                            'label' => $t->translate('Configurations'),
                            'id' => 'configs',
                            'controller' => 'config',
                            'action' => 'index',
                            'route' => 'default',
                            'ac' => array(
                                'area' => 'config'
                            ),
                            'icon' => 'tm-configuration'
                        ),
                        array(
                            'label' => $t->translate('Syndication'),
                            'id' => 'syndication',
                            'controller' => 'syndication',
                            'action' => 'index',
                            'route' => 'default',
                            'ac' => array(
                                'area' => 'syndication'
                            ),
                            'icon' => 'tm-syndication'
                        ),
                        array(
                            'label' => $t->translate('Translations'),
                            'id' => 'translation',
                            'controller' => 'translation',
                            'action' => 'index',
                            'route' => 'default',
                            'ac' => array(
                                'area' => 'translation'
                            ),
                            'icon' => 'tm-translation'
                        ),
                        array(
                            'label' => $t->translate('Scripts'),
                            'id' => 'scripts',
                            'controller' => 'script',
                            'action' => 'index',
                            'route' => 'default',
                            'ac' => array(
                                'area' => 'script'
                            ),
                            'icon' => 'tm-script'
                        )
                    )
                ),
                array(
                    'label' => 'System',
                    'id' => 'system',
                    'controller' => 'plugins',
                    'action' => 'index',
                    'route' => 'default',
                    'pages' => $this->_getPlugins(array('Aitsu', 'Moraso')),
                    'ac' => array(
                        'area' => 'plugins'
                    ),
                    'icon' => 'tm-management'
                ), $pluginsNav,
                array(
                    'label' => $t->translate('Logout'),
                    'id' => 'logout',
                    'controller' => 'acl',
                    'action' => 'logout',
                    'route' => 'default'
                )
            );

            Zend_Registry::set('nav', new Zend_Navigation($nav));
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    protected function _getPlugins($with = array(), $without = array()) {

        $user = Aitsu_Adm_User::getInstance();

        $namespaces = Moraso_Plugins::getNamespaces();

        $plugins = array();
        foreach ($namespaces as $namespace) {

            if ((empty($without) && in_array($namespace, $with)) || (empty($with) && !in_array($namespace, $without))) {

                $pluginDir = APPLICATION_LIBPATH . '/' . $namespace . '/Plugin';

                $files = Aitsu_Util_Dir::scan($pluginDir, 'Class.php');
                $baseLength = strlen($pluginDir);

                foreach ($files as $plugin) {
                    $pluginXml = realpath(dirname($plugin) . '/plugin.xml');
                    $pluginInfo = simplexml_load_file($pluginXml);
                    $pluginPathInfo = explode('/', substr($plugin, $baseLength + 1));
                    $pluginType = $pluginPathInfo[1];
                    $pluginName = $pluginPathInfo[0];

                    if ($pluginType === 'Generic') {
                        $aclAreaCheck = 'plugin.' . strtolower($pluginName) . '.generic';

                        if ($user != null && $user->isAllowed(array('area' => $aclAreaCheck))) {
                            $plugins[] = array(
                                'label' => (string) $pluginInfo->name,
                                'id' => uniqid(),
                                'controller' => 'plugin',
                                'params' => array(
                                    'namespace' => $namespace,
                                    'plugin' => $pluginName,
                                    'paction' => 'index'
                                ),
                                'route' => 'gplugin',
                                'pages' => array(),
                                'icon' => (string) $pluginInfo->icon
                            );
                        }
                    }
                }
            }
        }

        return $plugins;
    }

}