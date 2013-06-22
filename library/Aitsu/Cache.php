<?php


/**
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2012, w3concepts AG
 */
class Aitsu_Cache {

	protected function __construct() {
	}

	public static function getInstance($id = null, $allowCacheIfLoggedIn = false) {

		static $instance;
		static $sid = null;

		$sid = is_null($id) ? $sid : $id;
		$sid = is_null($sid) ? 'app' : $sid;

		if (!isset ($instance)) {
			$type = Aitsu_Config :: get('cache.type');
			if ($type == 'apc') {
				$instance = new Aitsu_Cache_Apc();
			} else {
				$instance = new Aitsu_Cache_File();
			}
		}

		$instance->setId($sid);
		$instance->setCacheIfLoggedIn($allowCacheIfLoggedIn);

		return $instance;
	}
}