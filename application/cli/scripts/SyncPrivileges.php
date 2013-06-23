<?php

/**
 * @author Christian Kehres <c.kehres@webtischlerei.de>
 * @copyright (c) 2013, webtischlerei <http://www.webtischlerei.de>
 */
class Cli_SyncPrivileges extends Aitsu_Cli_Script_Abstract {

    protected function _main() {

        include_once APPLICATION_PATH . '/adm/scripts/Maintenance/Synchronize_Privileges.php';

        $response = 'START';
        $counter = 1;
        while ($response != null) {
            $updateScript = new Adm_Script_Synchronize_Privileges($counter);
            $response = $updateScript->exec()->toArray();
            echo $counter . ': ' . $response['message'] . "\n";
            if (empty($response['nextStep'])) {
                $response = null;
            }
            if ($response['nextStep'] != 'RESUME') {
                $counter++;
            }
        }

        echo "\n" . 'Script execution terminated.' . "\n";
    }

}