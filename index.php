<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
define('REQUEST_START', microtime(true));

$dirPath = dirname(__FILE__);

define('APPLICATION_PATH', $dirPath . '/application');
define('LIBRARY_PATH', $dirPath . '/library');
define('CACHE_PATH', APPLICATION_PATH . '/data/pagecache');

require_once (LIBRARY_PATH . '/Moraso/Bootstrap.php');

define('REQUEST_HASH', sha1(serialize(array_merge($_REQUEST, array($_SERVER['HTTP_HOST'])))));

echo Moraso_Bootstrap::run()->getOutput();
exit(0);