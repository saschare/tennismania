<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Adm_Script_Synchronize_Privileges extends Aitsu_Adm_Script_Abstract {

    protected $_methodMap = array();
    protected $_privileges = null;

    public static function getName() {

        return Aitsu_Translate::translate('Synchronize Privileges');
    }

    protected function _init() {

        $this->_methodMap = array(
            '_beforeStart',
            '_setPrivileges'
        );

        $plugins = Aitsu_Util_Dir::scan(APPLICATION_LIBPATH, 'plugin.xml');

        foreach ($plugins as $plugin) {
            $pluginInfo = simplexml_load_file($plugin);

            foreach ($pluginInfo->privileges->privilege as $privilege) {
                $this->_privileges[] = (string) $privilege->identifier;
            }
        }

        $privileges = simplexml_load_file(APPLICATION_PATH . '/privileges.xml');

        foreach ($privileges->privilege as $privilege) {
            $this->_privileges[] = (string) $privilege->identifier;
        }
    }

    protected function _beforeStart() {

        return Aitsu_Translate::translate('The process may take quite a while. Please be patient.');
    }

    protected function _setPrivileges() {

        $privileges = Moraso_Db::fetchAll('' .
                        'SELECT ' .
                        '   privilegeid, ' .
                        '   identifier ' .
                        'FROM ' .
                        '   _acl_privilege');

        $removedPrivileges = array();
        foreach ($privileges as $privilege) {
            if (!in_array($privilege['identifier'], $this->_privileges)) {
                $removedPrivileges[] = $privilege['privilegeid'];
                Aitsu_Db::query('DELETE FROM _acl_privilege WHERE privilegeid =:privilegeid', array(':privilegeid' => $privilege['privilegeid']));
            }
        }

        $newPrivileges = array();
        foreach ($this->_privileges as $privilege) {

            $privilegeid = Moraso_Db::fetchOne('' .
                            'SELECT ' .
                            '   privilegeid ' .
                            'FROM ' .
                            '   _acl_privilege ' .
                            'WHERE ' .
                            '   identifier =:privilege', array(
                        ':privilege' => $privilege
            ));

            if (empty($privilegeid)) {
                $privilegeid = Aitsu_Db::put('_acl_privilege', 'privilegeid', array(
                            'identifier' => $privilege
                ));

                Aitsu_Db::put('_acl_privileges', null, array(
                    'roleid' => 18,
                    'privilegeid' => $privilegeid
                ));

                $newPrivileges[] = $privilegeid;
            }
        }

        if (!empty($newPrivileges)) {
            return Aitsu_Translate::translate('Privilegien wurden geprüft. Es wurden ' . count($newPrivileges) . ' neue Privilegien eingetragen und der Rolle "Admin" zugewiesen.');
        }
        
        if (!empty($removedPrivileges)) {
            return Aitsu_Translate::translate('Privilegien wurden geprüft. Es wurden keine neue Privilegien eingetragen und ' . count($removedPrivileges) . ' Privilegien entfernt.');
        }

        return Aitsu_Translate::translate('Privilegien wurden geprüft. Es wurden keine neue Privilegien eingetragen oder entfernt.');
    }

    protected function _hasNext() {

        if ($this->_currentStep < count($this->_methodMap)) {
            return true;
        }

        return false;
    }

    protected function _next() {

        return 'Next line to be executed.';
    }

    protected function _executeStep() {

        $method = $this->_methodMap[$this->_currentStep];
        $response = @call_user_func_array(array(
                    $this,
                    $method
                        ), array());

        if (is_object($response)) {
            return Aitsu_Adm_Script_Response::factory($response->message, 'warning');
        }

        return Aitsu_Adm_Script_Response::factory($response);
    }

}