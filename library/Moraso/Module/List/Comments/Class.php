<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Moraso_Module_List_Comments_Class extends Moraso_Module_Abstract
{
    protected function _getDefaults()
    {
        $defaults = array(
            'template' => 'index',
            'startLevel' => 1,
            'maxLevel' => 2
        );

        return $defaults;
    }

    protected function _main()
    {
        $defaults = $this->_moduleConfigDefaults;

        $translation = array();
        $translation['configuration'] = Aitsu_Translate::_('Configuration');

        /* Configuration */
        if ($defaults['configurable']['template']) {
            $template = Aitsu_Content_Config_Select::set($this->_index, 'template', Aitsu_Translate::_('Template'), $this->_getTemplates(), $translation['configuration']);
        }

        $template = !empty($template) ? $template : $defaults['template'];

        /* get Data */
        $node_id = Aitsu_Persistence_ArticleProperty::factory(Aitsu_Registry::get()->env->idartlang)->comments['node_id']->value;
        $comments = Moraso_Comments::getComments($node_id, $defaults['startLevel'], $defaults['maxLevel']);
        
        /* create View */
        $view = $this->_getView();
        $view->comments = $comments;
        return $view->render($template . '.phtml');
    }

    protected function _cachingPeriod()
    {

        return 0;
    }

}