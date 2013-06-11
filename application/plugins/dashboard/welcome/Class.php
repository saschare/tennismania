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

        if (date('H') >= 18) {
            $this->view->greeting = 'Guten Abend';
        } elseif (date('H') >= 10) {
            $this->view->greeting = 'Guten Tag';
        } else {
            $this->view->greeting = 'Guten Morgen';
        }

        $user = Aitsu_Adm_User::getInstance();
        $this->view->user = $user->lastname;

        $todos = Aitsu_Db::fetchOne('' .
                        'SELECT' .
                        '   count(*) ' .
                        'FROM ' .
                        '   _todo ' .
                        'WHERE ' .
                        '   status =:status ' .
                        'AND ' .
                        '   userid =:userid', array(
                    ':status' => 0,
                    ':userid' => $user->userid
        ));

        if (empty($todos)) {
            $this->view->todos = 'sind keine Aufgaben';
        } else {
            if ($todos == 1) {
                $this->view->todos = 'ist ' . $todos . ' Aufgabe';
            } else {
                $this->view->todos = 'sind ' . $todos . ' Aufgaben';
            }
        }
    }

}