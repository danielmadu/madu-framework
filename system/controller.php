<?php
defined('CONTROLLERS') OR die('Acesso direto ao arquivo negado.');

	class Controller {
		public $_title;
		public $_cabecalho;
		public $_rodape;
		protected function view($view, $vars = null){
			$title = $this->_title;
			if(is_array($vars) && count($vars)>0){
				extract($vars, EXTR_PREFIX_ALL, 'view');
			}
			$view_render = 'app/views/'.$view.'.phtml';
			if(file_exists($view_render)){
				return require_once $view_render;
			} else {
				die("View <b>{$view}</b> n&atilde;o existe");
			}
			//exit();
			
		}
		
		protected function template($corpo, $vars = null){
			
			$this->view($this->_cabecalho, $vars);
			$this->view($corpo, $vars);
			$this->view($this->_rodape, $vars);
		}
		
		protected function getParam($param=0){
			$query = explode('/', $_SERVER['QUERY_STRING']);
			return $query[$param];
		}
		
		
	}
?>