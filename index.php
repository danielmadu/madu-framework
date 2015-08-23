<?php
	
	define('CONTROLLERS', 'app/controllers/');
	define('VIEWS', 'app/views/');
	define('MODELS', 'app/models/');
	define('HELPERS', 'system/helpers/');
	define('TERCEIROS', 'system/3rd/');
	
	
	function __autoload($file){
		$models = MODELS.$file.'.php';
		$helpers = HELPERS.$file.'.php';

		if(file_exists($models)) {
			require_once $models;
		}
		else if(file_exists($helpers)) {
			require_once $helpers;
		} 
		else if(file_exists($terceiros)) {
			require_once $terceiros;
		} 
		else {
			die("Classe <b>{$file}</b> n&atilde;o existe<br />");
		}
	}
	
	//Arquivo de configuração
	require_once 'system/config.php';

	//Incluíndo classes básicas do framework
	require_once 'system/system.php';
	require_once 'system/controller.php';
	require_once 'system/model.php';
	
	$start = new System();
	

	//Tratando os parametros passados via url
	$_GET['key'] = (isset($_GET['key']) ? $_GET['key'].'/' : 'index/index');
	$key = $_GET['key'];
	$separator = explode('/',$key);
	$controller = $separator[0];
	$action = ($separator[1] == null ? 'index' : $separator[1]);
	
	
	$filename = CONTROLLERS.$controller.'Controller.php';
	if(!file_exists($filename)){
		die("Controller <b>{$filename}</b> n&atilde;o existe<br />");
	}
			
	//Executando Actions requisitadas
	
	require_once CONTROLLERS.$controller.'Controller.php';
	$controller = $controller.'Controller';
	$app = new $controller();
	
	$action = $action.'_action';	
	if(!method_exists($app, $action)){
		die("Action <b>{$action}</b> n&atilde;o existe<br />");
	}
	$app->$action();	

?>