<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
clASs Moraso_Rewrite_Standard extends Aitsu_Rewrite_Abstract {

    private static $_instance = null;

    public static function getInstance() {

        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    final private function __construct() {
        
    }

    final private function __clone() {
        
    }

    public function register() {

        return empty(Aitsu_Registry::get()->env->idlang);
    }

    public function registerParams() {

        if (substr($_GET['url'], -5) == '.html') {
            $pathInfo = pathinfo($_GET['url']);
            $url = $pathInfo['dirname'];
            $urlname = $pathInfo['filename'];
        } else {
            $url = substr($_GET['url'], -1) == '/' ? substr($_GET['url'], 0, -1) : $_GET['url'];
            $urlname = null;
        }

        $idcat = Moraso_Config::get('sys.startcat');
        $idlang = Moraso_Config::get('sys.language');
        $idclient = Moraso_Config::get('sys.client');

        if (empty($url)) {
            $result = Moraso_Db::fetchRowC('eternal', '' .
                            'SELECT ' .
                            '   artlang.idart, ' .
                            '   artlang.idlang, ' .
                            '   artlang.idartlang, ' .
                            '	catlang.idcat, ' .
                            '	cat.idclient ' .
                            'FROM ' .
                            '   _art_lang AS artlang ' .
                            'LEFT JOIN ' .
                            '   _cat_lang AS catlang ON artlang.idartlang = catlang.startidartlang ' .
                            'LEFT JOIN ' .
                            '   _cat AS cat ON catlang.idcat = cat.idcat ' .
                            'WHERE ' .
                            '	catlang.idcat =:idcat ' .
                            'AND' .
                            '   artlang.idlang =:idlang', array(
                        ':idcat' => $idcat,
                        ':idlang' => $idlang
            ));
        } elseif (Aitsu_Registry::get()->config->rewrite->uselang && preg_match('@^\\w*/?$@', $url)) {
            $result = Moraso_Db::fetchRowC('eternal', '' .
                            'SELECT ' .
                            '   artlang.idart, ' .
                            '   artlang.idlang, ' .
                            '   artlang.idartlang, ' .
                            '	catlang.idcat, ' .
                            '	lang.idclient ' .
                            'FROM ' .
                            '   _art_lang AS artlang ' .
                            'LEFT JOIN ' .
                            '   _cat_lang AS catlang ON artlang.idartlang = catlang.startidartlang ' .
                            'LEFT JOIN ' .
                            '   _lang AS lang ON catlang.idlang = lang.idlang ' .
                            'WHERE ' .
                            '	catlang.idcat =:idcat ' .
                            'AND ' .
                            '   lang.name =:langname ' .
                            'AND ' .
                            '   lang.idclient =:client ', array(
                        ':idcat' => $idcat,
                        ':langname' => $url,
                        ':client' => $idclient
            ));
        } else {
            if ($urlname == null) {
                $result = Moraso_Db::fetchRowC('eternal', '' .
                                'SELECT ' .
                                '   artlang.idart, ' .
                                '   artlang.idlang, ' .
                                '   artlang.idartlang, ' .
                                '   catlang.idcat, ' .
                                '   cat.idclient ' .
                                'FROM ' .
                                '   _art_lang AS artlang ' .
                                'LEFT JOIN ' .
                                '   _cat_lang AS catlang ON artlang.idartlang = catlang.startidartlang ' .
                                'LEFT JOIN ' .
                                '   _cat AS cat ON catlang.idcat = cat.idcat ' .
                                'where ' .
                                '   catlang.url =:url ' .
                                'AND ' .
                                '   cat.idclient =:client ', array(
                            ':url' => $url,
                            ':client' => $idclient
                ));
            } else {
                $result = Moraso_Db::fetchRowC('eternal', '' .
                                'SELECT ' .
                                '   artlang.idart, ' .
                                '   artlang.idlang, ' .
                                '   artlang.idartlang, ' .
                                '   catlang.idcat, ' .
                                '   cat.idclient ' .
                                'FROM ' .
                                '   _art_lang AS artlang ' .
                                'LEFT JOIN ' .
                                '   _cat_art AS catart ON artlang.idart = catart.idart ' .
                                'LEFT JOIN ' .
                                '   _cat_lang AS catlang ON catart.idcat = catlang.idcat AND artlang.idlang = catlang.idlang ' .
                                'LEFT JOIN ' .
                                '   _cat AS cat ON catlang.idcat = cat.idcat ' .
                                'where ' .
                                '   artlang.urlname =:urlname ' .
                                'AND ' .
                                '   catlang.url =:url ' .
                                'AND ' .
                                '   cat.idclient =:client ', array(
                            ':urlname' => $urlname,
                            ':url' => $url,
                            ':client' => $idclient
                ));
            }
        }

        if ($result) {
            Aitsu_Registry::get()->env->idart = $result['idart'];
            Aitsu_Registry::get()->env->idcat = $result['idcat'];
            Aitsu_Registry::get()->env->idlang = $result['idlang'];
            Aitsu_Registry::get()->env->idartlang = $result['idartlang'];
            Aitsu_Registry::get()->env->idclient = $result['idclient'];
            return;
        } else {
            if (Aitsu_Registry::get()->config->rewrite->uselang) {
                $getIdLang = Moraso_Db::fetchOne('' .
                                'SELECT ' .
                                '   idlang ' .
                                'FROM ' .
                                '   _lang ' .
                                'WHERE ' .
                                '   name =:name ', array(
                            ':name' => strtok($_GET['url'], '/')
                ));

                if ($getIdLang) {
                    $idlang = $getIdLang;
                }
            }

            Aitsu_Registry::get()->env->idlang = $idlang;
        }
    }

    public function rewriteOutput($html) {

        $this->_populateMissingUrls(Aitsu_Registry::get()->env->idlang);

        $matches = array();
        if (preg_match_all('/\\{ref:(idcat|idart)\\-(\\d+)\\}/s', $html, $matches) == 0) {
            return $html;
        }

        $matches[0] = array_unique($matches[0], SORT_REGULAR);

        $baseUrl = Moraso_Config::get('sys.webpath');

        $idarts = array();
        $idcats = array();
        foreach (array_keys($matches[0]) AS $key) {
            if ($matches[1][$key] == 'idart') {
                $idarts[$matches[2][$key]] = $matches[0][$key];
            } elseif ($matches[1][$key] == 'idcat') {
                $idcats[$matches[2][$key]] = $matches[0][$key];
            }
        }

        if (empty($idarts) && empty($idcats)) {
            return $html;
        }

        $rewriteSearch = array();
        $rewriteReplace = array();

        if (!empty($idarts)) {
            $results = Moraso_Db::fetchAll('' .
                            'SELECT ' .
                            '	artlang.idart AS idart, ' .
                            '	CONCAT(catlang.url, \'/\', artlang.urlname, \'.html\') AS url ' .
                            'FROM ' .
                            '   _art_lang AS artlang ' .
                            'INNER JOIN ' .
                            '   _cat_art AS catart ON artlang.idart = catart.idart ' .
                            'INNER JOIN ' .
                            '   _cat_lang AS catlang ON (artlang.idlang = catlang.idlang AND catart.idcat = catlang.idcat)' .
                            'WHERE ' .
                            '	artlang.idart IN (' . implode(',', array_keys($idarts)) . ') ' .
                            'AND ' .
                            '   artlang.idlang =:idlang', array(
                        ':idlang' => Aitsu_Registry::get()->env->idlang
            ));
            
            if ($results) {
                foreach ($results AS $article) {
                    $cache = Aitsu_Cache::getInstance('rewriting_idart_' . $article['idart'], true);
                    $cache->setLifetime(Aitsu_Util_Date::secONdsUntilEndOf('year'));

                    if ($cache->isValid()) {
                        $url = $cache->load();
                    } else {
                        $url = $baseUrl . $article['url'];
                        $cache->save($url, array('rewriting'));
                    }

                    $rewriteSearch[] = $idarts[$article['idart']];
                    $rewriteReplace[] = $url;
                }
            }
        }

        if (!empty($idcats)) {
            $results = Moraso_Db::fetchAll('' .
                            'SELECT ' .
                            '	catlang.idcat AS idcat, ' .
                            '	catlang.url AS url ' .
                            'FROM ' .
                            '   _cat_lang AS catlang ' .
                            'WHERE ' .
                            '	catlang.idcat IN (' . implode(',', array_keys($idcats)) . ') ' .
                            'AND ' .
                            '   catlang.idlang =:idlang ', array(
                        ':idlang' => Aitsu_Registry::get()->env->idlang
            ));

            if ($results) {
                foreach ($results AS $category) {
                    $cache = Aitsu_Cache::getInstance('rewriting_idcat_' . $category['idcat'], true);
                    $cache->setLifetime(Aitsu_Util_Date::secONdsUntilEndOf('year'));

                    if ($cache->isValid()) {
                        $url = $cache->load();
                    } else {
                        $url = $baseUrl . $category['url'] . '/';
                        $cache->save($url, array('rewriting'));
                    }

                    $rewriteSearch[] = $idcats[$category['idcat']];
                    $rewriteReplace[] = $url;
                }
            }
        }

        return preg_replace('/\\{ref:(idcat|idart)\\-(\\d+)\\}/s', '/', str_replace($rewriteSearch, $rewriteReplace, $html));
    }

    protected function _populateMissingUrls($idlang) {

        $categoriesWithoutUrl = Moraso_Db::fetchOne('' .
                        'SELECT ' .
                        '   COUNT(idcat) ' .
                        'FROM ' .
                        '   _cat_lang ' .
                        'WHERE ' .
                        '   url IS NULL ' .
                        'AND ' .
                        '   idlang =:idlang', array(
                    ':idlang' => $idlang
        ));

        if (empty($categoriesWithoutUrl)) {
            return;
        }

        Moraso_Db::startTransaction();

        try {
            Moraso_Db::query('' .
                    'UPDATE ' .
                    '   _cat_lang AS catlang, ' .
                    '   _lang AS lang, ' .
                    '	( ' .
                    '       SELECT ' .
                    '           child.idcat AS idcat, ' .
                    '		GROUP_CONCAT(catlang.urlname ORDER BY parent.lft ASC SEPARATOR \'/\') AS url ' .
                    '       FROM ' .
                    '           _cat AS child ' .
                    '       LEFT JOIN ' .
                    '           _cat AS parent ON child.lft BETWEEN parent.lft AND parent.rgt ' .
                    '       LEFT JOIN ' .
                    '           _cat_lang AS catlang ON parent.idcat = catlang.idcat AND parent.parentid > 0 ' .
                    '       WHERE ' .
                    '           catlang.idlang =:idlang ' .
                    '       GROUP BY ' .
                    '           child.idcat ' .
                    '	) AS url ' .
                    'SET ' .
                    '   catlang.url = CONCAT(lang.name, \'/\', url.url) ' .
                    'WHERE ' .
                    '	catlang.idcat = url.idcat ' .
                    'AND ' .
                    '   catlang.idlang =:idlang ' .
                    'AND ' .
                    '	lang.idlang =:idlang', array(
                ':idlang' => $idlang
            ));

            Moraso_Db::query('' .
                    'UPDATE ' .
                    '   _cat_lang AS catlang ' .
                    'SET ' .
                    '   catlang.url = \'\' ' .
                    'WHERE ' .
                    '   catlang.url IS NULL', array(
                ':idlang' => $idlang
            ));

            Moraso_Db::commit();
        } catch (Exception $e) {
            Moraso_Db::rollback();
            trigger_error('Exception in ' . __FILE__ . ' on line ' . __LINE__);
            trigger_error('Message: ' . $e->getMessage());
            trigger_error($e->getTraceAsString());
        }
    }

}