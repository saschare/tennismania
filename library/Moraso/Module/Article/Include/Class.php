<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_Article_Include_Class extends Moraso_Module_Abstract {

    protected function _getDefaults() {

        $defaults = array(
            'template' => 'index'
        );

        return $defaults;
    }

    protected function _main() {

        $defaults = $this->_moduleConfigDefaults;

        $idart_source = Aitsu_Content_Config_Link::set($this->_index, 'Article.Include.Source', 'Source', Aitsu_Translate::_('Source article'));
        $idart = preg_replace('/[^0-9]/', '', $idart_source);

        if (empty($idart) || strpos($idart_source, 'idart') === false) {
            return '';
        }

        $view = $this->_getView();

        $idlang = Moraso_Util::getIdlang();

        $idartlang = Moraso_Util::getIdArtLang($idart, $idlang);

        $article = Aitsu_Persistence_Article::factory($idart, $idlang)->load();

        $selectTemplate = Aitsu_Content_Config_Select::set($this->_index, 'template', Aitsu_Translate::_('Template'), $this->_getTemplates(), Aitsu_Translate::_('Configuration'));

        $view->content = Moraso_Db::fetchAll('' .
                        'SELECT ' .
                        '   `index`, ' .
                        '   value ' .
                        'FROM ' .
                        '   _article_content ' .
                        'WHERE ' .
                        '   idartlang =:idartlang', array(
                    ':idartlang' => $idartlang
        ));

        $view->images = Aitsu_Core_File::getFiles($idartlang);
        $view->tags = $article->getTags();
        $view->data = $article->getData();

        $template = !empty($selectTemplate) ? $selectTemplate : $defaults['template'];

        if (!in_array($template, $this->_getTemplates())) {
            return '';
        }

        return $view->render($template . '.phtml');
    }

    protected function _cachingPeriod() {

        return Aitsu_Util_Date::secondsUntilEndOf('day');
    }

}