<?php


/**
 * Select configuration.
 * 
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2010, w3concepts AG
 * 
 * {@id $Id: Select.php 16750 2010-06-02 08:15:42Z akm $}
 */

class Aitsu_Config_Select extends Aitsu_Content_Config_Abstract {
	
	public function getTemplate() {

		return 'Select.phtml';
	}
	
	public static function set($index, $name, $label, $keyValuePairs, $fieldset) {
		
		$instance = new self($index, $name);
		
		$instance->facts['fieldset'] = $fieldset;
		$instance->facts['label'] = $label;
		$instance->params['keyValuePairs'] = $keyValuePairs;
		
		return $instance->currentValue();
	}
}