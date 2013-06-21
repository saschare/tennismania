<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Article_Class extends Moraso_Module_Abstract {

    protected $_allowEdit = false;

    protected function _main() {

        /* get Data */
        $itemData = array(
            'price' => 17.95
        );

        /* create View */
        $view = $this->_getView();
        $view->itemData = (object) $itemData;
        return $view->render('index.phtml');
    }

    protected function _cachingPeriod() {

        return 0;
    }

}