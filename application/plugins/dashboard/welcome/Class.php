<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class welcomeDashboardController extends Aitsu_Adm_Plugin_Controller {

    const ID = '519f21c4-f818-4ce9-8a65-2fa0c0a8b230';

    public function init() {

        $this->_helper->layout->disableLayout();
        header("Content-type: text/javascript");
    }

    public static function register() {

        return (object) array(
                    'name' => 'welcome',
                    'tabname' => Aitsu_Translate::_('welcome'),
                    'enabled' => true,
                    'id' => self::ID
        );
    }

    public function indexAction() {
        
    }

}