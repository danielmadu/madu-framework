<?php
defined('CONTROLLERS') OR die('Acesso direto ao arquivo negado.');

	class paginaController extends Controller {
		
		public $_cabecalho = 'site/cabecalhoSite';
		public $_rodape = 'site/rodapeSite';
		public $_res = array();
		
		function __construct() {
			//$bd = new Galeria_Model();
			//$this->_res['destaque'] = $bd->read('destaque=1');
			
			$bd = new Paginas_Model();
			$resultado = $bd->read('id=3');
			$this->_res['rodape'] = $resultado[0]['texto'];
			
			$bd = new Template_Model();
			$css = $bd->read();
			$css = $css[0];
			$this->_res['logo'] = $css['logo'];
			$body .= 'body { background-color: '.$css['background'].';';
			if($css['backgroundImagem']){
				 $body .= 'background-image: url("uploads/fundo_'.$css['backgroundImagem'].'");';
				 $body .= 'background-repeat: repeat;';
			}
			$body .= '}';
			
			$this->_res['css'] = 
			$body.'
			.navbar {
				background-color: '.$css['navbar'].';
				border-color: '.$css['navbar'].';
				color: '.$css['navbarFont'].';
			}
			#conteudo {
				background-color: '.$css['conteudo'].';
				color: '.$css['conteudoFont'].';
			}
			#Rodape {
				background-color: '.$css['rodape'].';
				color: '.$css['rodapeFont'].';
				
			}
			.navbar .navbar-text {
				color: '.$css['navbarFont'].';
			}
			
			.navbar .navbar-link {
				color: '.$css['navbarFont'].';
			}
			.navbar .navbar-link:hover {
				background-color: '.$css['navbar'].';
				color: '.$css['navbarFont'].';
			}
			
			';
			
		}
		
		public function index_action()
		{
			$this->redirect('/');
		}
		
		public function sobre_action()
		{
			$this->_title = 'Meu Site - Sobre';
			$bd = new Paginas_Model();
			$resultado = $bd->read('id=2');
			$this->_res['titulo'] = $resultado[0]['titulo'];
			$this->_res['texto'] = $resultado[0]['texto'];
			$this->template('site/sobre',$this->_res);
			
		}
		
		public function contato_action()
		{
			$this->_title = 'Meu Site - Contato';
			$bd = new Paginas_Model();
			$resultado = $bd->read('id=1');
			$this->_res['titulo'] = $resultado[0]['titulo'];
			$this->_res['texto'] = $resultado[0]['texto'];
			$this->_res['contato'] = 1;
			$this->template('site/contato',$this->_res);
			
		}
		
	}
	
?>
		