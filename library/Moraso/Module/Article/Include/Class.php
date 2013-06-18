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

        if (empty($idart)) {
            return '';
        }

        $idlang = Moraso_Util::getIdlang();

        $idartlang = Moraso_Util::getIdArtLang($idart, $idlang);

        $article = Aitsu_Persistence_Article::factory($idart, $idlang)->load();

        $selectTemplate = Aitsu_Content_Config_Select::set($this->_index, 'template', Aitsu_Translate::_('Template'), $this->_getTemplates(), Aitsu_Translate::_('Configuration'));

        /* set Variables */
        $content = Aitsu_Db::fetchAll('' .
                        'SELECT ' .
                        '   `index`, ' .
                        '   value ' .
                        'FROM ' .
                        '   _article_content ' .
                        'WHERE ' .
                        '   idartlang =:idartlang', array(
                    ':idartlang' => $idartlang
        ));
        $images = Aitsu_Core_File::getFiles($idartlang);
        $tags = $article->getTags();
        $data = $article->getData();

        $template = !empty($selectTemplate) ? $selectTemplate : $defaults['template'];

        /* create View */
        $view = $this->_getView();

        $view->content = $content;
        $view->images = $images;
        $view->tags = $tags;
        $view->data = $data;

        return $view->render($template . '.phtml');
    }

    protected function _cachingPeriod() {

        return 'aternal';
    }

}