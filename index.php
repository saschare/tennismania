<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */

        const MIN_VERS_PHP = '5.3.2';

if (version_compare(PHP_VERSION, MIN_VERS_PHP, '<'))) {
    header('Content-type: text/html; charset=utf-8', true, 503);

    echo '<h2>Fehler</h2>';

    if (version_compare(PHP_VERSION, MIN_VERS_PHP, '<')) {
        echo 'Auf Ihrem Server läft PHP version ' . PHP_VERSION . ', moraso benötigt mindestens PHP ' . MIN_VERS_PHP . '!';
    }

    return;
}

define('REQUEST_START', microtime(true));
define('ROOT_PATH', dirname(__FILE__));

require_once (ROOT_PATH . '/library/Moraso/Bootstrap.php');

Moraso_Bootstrap::run();