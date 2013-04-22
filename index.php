<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
define('REQUEST_START', microtime(true));
define('ROOT_PATH', dirname(__FILE__));

require_once (ROOT_PATH . '/library/Moraso/Bootstrap.php');

echo Moraso_Bootstrap::run()->getOutput();

exit(0);