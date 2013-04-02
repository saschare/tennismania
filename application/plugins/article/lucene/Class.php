<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class LuceneArticleController extends Aitsu_Adm_Plugin_Controller {

    const ID = '5156f61e-d780-4b15-aa48-2a37c0a8b230';

    public function init() {

        header("Content-type: text/javascript");
        $this->_helper->layout->disableLayout();
    }

    public static function register($idart) {

        return (object) array(
                    'name' => 'lucene',
                    'tabname' => Aitsu_Registry::get()->Zend_Translate->translate('Lucene'),
                    'enabled' => self::getPosition($idart, 'lucene'),
                    'position' => self::getPosition($idart, 'lucene'),
                    'id' => self::ID
        );
    }

    public function indexAction() {

        $idart = $this->getRequest()->getParam('id');
        $idlang = Moraso_Util::getIdlang();

        $form = Aitsu_Forms::factory('lucene', APPLICATION_PATH . '/plugins/article/lucene/forms/lucene.ini');
        $form->title = Aitsu_Translate::translate('Lucene');
        $form->url = $this->view->url(array('plugin' => 'lucene', 'paction' => 'delete'), 'aplugin');

        $data = Moraso_Db::fetchRow('' .
                        'select ' .
                        '   uid, ' .
                        '   lastindexed ' .
                        'from ' .
                        '   _lucene_index ' .
                        'where ' .
                        '   idart =:idart ' .
                        'and ' .
                        '   idlang =:idlang', array(
                    ':idart' => $idart,
                    ':idlang' => $idlang
        ));

        $form->setValues($data);

        $client_data = Aitsu_Persistence_Clients::factory(Aitsu_Registry::get()->session->currentClient);

        $client_config = new Zend_Config_Ini('application/configs/clients/' . $client_data->config . '.ini', Moraso_Util::getEnv());

        $form->setValue('luceneIndex', $client_config->search->lucene->index);

        $index = Zend_Search_Lucene::open(APPLICATION_PATH . '/data/lucene/' . $client_config->search->lucene->index . '/');
        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('UTF-8');

        $hits = $index->find('uid:' . $data['uid'] . ' AND lang:' . $idlang . ' AND idart:' . $idart);

        if (isset($hits[0]) && !empty($hits[0])) {
            $luceneDocument = $hits[0]->getDocument();

            $form->setValue('uid', $luceneDocument->uid);
            $form->setValue('title', $luceneDocument->title);
            $form->setValue('pagetitle', $luceneDocument->pagetitle);
            $form->setValue('summary', $luceneDocument->summary);
        }

        if ($this->getRequest()->getParam('loader')) {
            $this->view->form = $form;
            header("Content-type: text/javascript");
            return;
        }
    }

    public function deleteAction() {

        $uid = $this->getRequest()->getParam('uid');
        $luceneIndex = $this->getRequest()->getParam('luceneIndex');

        $explode = explode('-', $uid);

        $index = Zend_Search_Lucene::open(APPLICATION_PATH . '/data/lucene/' . $luceneIndex . '/');
        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('UTF-8');

        $hits = $index->find('uid:' . $uid . ' AND lang:' . $explode[1] . ' AND idart:' . $explode[0]);

        $index->delete($hits[0]->id);

        Moraso_Db::query('' .
                'delete from ' .
                '   _lucene_index ' .
                'where ' .
                '   uid =:uid', array(
            ':uid' => $uid
        ));

        $data = array(
            'uid' => '',
            'title' => '',
            'pagetitle' => '',
            'summary' => ''
        );

        $this->_helper->json((object) array(
                    'success' => true,
                    'data' => (object) $data
        ));
    }

}