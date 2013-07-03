<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Cart_Article_Add_Class extends Moraso_Module_Abstract
{
    protected $_allowEdit = false;

    protected function _main()
    {
        return $this->_getView()->render('index.phtml');
    }

    protected function _cachingPeriod()
    {
        return 'eternal';
    }

}