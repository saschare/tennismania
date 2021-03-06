<?php


/**
 * Transformation interface
 * 
 * @version 1.0.0
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2010, w3concepts AG
 * 
 * {@id $Id: Interface.php 16535 2010-05-21 08:59:30Z akm $}
 */

interface Aitsu_Transformation_Interface {
	
	public static function getInstance();
	
	public function getContent($content);
	 
}