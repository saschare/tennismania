<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */

        const MIN_VERS_MYSQL = '5.1';
        const MIN_VERS_PHP = '5.3.2';

if (version_compare(PHP_VERSION, MIN_VERS_PHP, '<') || version_compare(mysql_get_client_info(), MIN_VERS_MYSQL, '<')) {
    header('Content-type: text/html; charset=utf-8', true, 503);

    echo '<h2>Fehler</h2>';

    if (version_compare(PHP_VERSION, MIN_VERS_PHP, '<')) {
        echo 'Auf Ihrem Server läft PHP version ' . PHP_VERSION . ', moraso benötigt mindestens PHP ' . MIN_VERS_PHP . '!<br />';
    }

    if (version_compare(mysql_get_client_info(), MIN_VERS_MYSQL, '<')) {
        echo 'Auf Ihrem Server läuft MySQL version ' . mysql_get_client_info() . ', moraso benötigt mindestens MySQL ' . MIN_VERS_MYSQL . '!';
    }

    return;
}

define('REQUEST_START', microtime(true));
define('ROOT_PATH', dirname(__FILE__));

require_once (ROOT_PATH . '/library/Moraso/Bootstrap.php');

Moraso_Bootstrap::run();