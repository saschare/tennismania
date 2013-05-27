<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Google_Analytics_Class extends Moraso_Module_Abstract {

    protected function _main() {

        $view = $this->_getView();

        $view->account = $this->_params->account;
        $view->domainName = $this->_params->domainName;
        $view->allowLinker = $this->_params->allowLinker;

        if (empty($view->account)) {
            return '';
        }

        return $view->render('index.phtml');
    }

    protected function _cachingPeriod() {

        return 'eternal';
    }

}