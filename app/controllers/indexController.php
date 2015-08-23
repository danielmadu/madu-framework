<?php
defined('CONTROLLERS') OR die('Acesso direto ao arquivo negado.');

	class indexController extends Controller {
		
		public $_cabecalho = 'site/cabecalhoSite';
		public $_rodape = 'site/rodapeSite';
		public $_res = array();
		
		function __construct() {
			$bd = new Paginas_Model();
			$resultado = $bd->read('id=3');
			$this->_res['rodape'] = $resultado[0]['texto'];
		}

		public function index_action(){
			$this->_title = 'Meu Site';
			
			$bd = new GaleriasImagens_Model();
			$this->_res['destaque'] = $bd->read('destaque=1');
			//$res['galeria'] = $bd->read(null,null,null,'id DESC');
			
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
			
			
			$this->template('site/site',$this->_res);
		}
			
		public function login_action()
		{
			$chave = '%h8l5*l2e9hjw2462d16asd617#$d';
			if($_POST['enviar']){
				$_POST['senha'] = md5($_POST['senha'].$chave);				
				$bd = new Acesso_Model();
				$retorno = $bd->read("usuario='{$_POST[usuario]}' AND senha='{$_POST[senha]}'");
				if(count($retorno)>0) 
				{
					session_start();
					$_SESSION['id'] = $retorno[0]['id'];
					URL::redirect('/Site/admin');
				} 
				else 
				{
					$dados['erro'] = 'Usu&aacute;rio ou Senha inv&aacute;lido!';	
				}
			}
			$this->view('admin/loginAdmin',$dados);
		}			
		
		public function css_action()
		{

		}

		public function novo_action()
		{

		}
	}
?>