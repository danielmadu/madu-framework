<?php  
/**
 * 
 */
class TEMPLATE {
	
	public static function view($view, $vars = null)
	{
		if(is_array($vars) && count($vars)>0){
			extract($vars, EXTR_PREFIX_ALL, 'view');
		}
		$view_render = 'app/views/'.$view.'.phtml';
		if(file_exists($view_render)){
			return require_once $view_render;
		} else {
			die("View <b>{$view}</b> n&atilde;o existe");
		}
	}
}

?>