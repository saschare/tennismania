<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Adm_Script_Update_1_X_Auf_1_13 extends Aitsu_Adm_Script_Abstract {

    public static function getName() {

        return Aitsu_Translate::translate('Update 1.x auf 1.13');
    }

    public function doCheckIfExecutable() {

        $privilegeid = (int) Moraso_Db::fetchOne('' .
                        'select ' .
                        '   privilegeid ' .
                        'from ' .
                        '   _acl_privilege ' .
                        'where ' .
                        '   identifier =:identifier', array(
                    ':identifier' => 'plugin.management.configuration'
        ));

        if (!empty($privilegeid) || is_int($privilegeid)) {
            throw new Exception(Aitsu_Translate :: translate('Script has already been executed!'));
        }

        return Aitsu_Adm_Script_Response::factory('The script will run!');
    }

    public function doUpdate() {

        Moraso_Db::query('drop table if exists _moraso_config');

        $privilegeid = Moraso_Db::put('_acl_privilege', 'privilegeid', array(
                    'identifier' => 'plugin.management.configuration'
        ));

        Moraso_Db::put('_acl_privileges', null, array(
            'roleid' => 18,
            'privilegeid' => $privilegeid
        ));

        return Aitsu_Adm_Script_Response::factory(Aitsu_Translate::translate('Script finished!'));
    }

}