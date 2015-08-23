<?php  
defined('CONTROLLERS') OR die('Acesso direto ao arquivo negado.');

/**
 * 
 */
class Config {
	
	public static $base;
	
	public static function init($argument=array()) {
		$this->base = $argument['base_url'];
	}
}


?>