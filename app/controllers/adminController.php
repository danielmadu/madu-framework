<?php  
defined('CONTROLLERS') OR die('Acesso direto ao arquivo negado.');
session_start();
if(!isset($_SESSION['id'])) URL::redirect('/Site/index/login');

require_once TERCEIROS.'canvas/canvas.php';
/**
 * 
 */
class adminController extends Controller {
	
	public $_cabecalho = 'admin/cabecalhoAdmin';
	public $_rodape = 'admin/rodapeAdmin';
	protected $_usuario = 1;
	
	function __construct() {
		
	}
	
	public function index_action()
	{
		$this->_title = 'Administrador - Início';
		$dados['lugar'] = 'home';
		$this->template('admin/indexAdmin',$dados);
	}
	
	public function email_action()
	{
		$this->_title = 'Administrador - E-mail';
		$dados['lugar'] = 'email';
		$this->template('admin/emailAdmin',$dados);
	}
	
	public function preferencias_action()
	{
		$this->_title = 'Administrador - Preferências';
		$dados['lugar'] = 'preferencias';
		$bd = new Paginas_Model();
		$dados['dados'] = $bd->read();
		$bd = new Template_Model();
		$css = $bd->read();
		$css = $css[0];
		$dados['css'] = $css;		
		$this->template('admin/preferenciasAdmin',$dados);
	}
	
	public function alterar_action()
	{
		$paginas = new Paginas_Model();
		$id = abs((int) $_POST['modificar']);
		unset($_POST['modificar'],$_POST['enviar']);
		$paginas->update($_POST,"id={$id}");
		HTML::certo('Texto atualizado.');
	}

	

	/**
	 * Galeria
	 * ==========
	 * Exibe a galeria
	 */

	public function galerias_action()
	{
		$this->_title = 'Administrador - Galerias';
		$dados['lugar'] = 'galerias';
		$this->template('admin/galeriasAdmin',$dados);
		
	}
	
	
	
	public function galeriasForm_action()
	{
		$id = abs((int)$this->getParam(3));
		if($id){
			$bd = new Galerias_Model();
			$dados['galeria'] = $bd->read("id=$id");
		} 
		$this->view('admin/galeriasFormAdmin',$dados);
	}
	
	public function galeriasSalvar_action()
	{
		$bd = new Galerias_Model();
		$id = abs((int) $_POST['modificar']);
		unset($_POST['modificar'],$_POST['enviar']);
		if($id){
			$bd->update($_POST,"id={$id}");
			HTML::certo('Galeria atualizada com sucesso.');
		} else {
			$_POST['usuario'] = $this->_usuario;
			$bd->insert($_POST);
			HTML::certo('Galeria criada com sucesso.');
		}
		
		//POG Temporário
		HTML::Imprime('<script>Abrir("#abreGaleriasListar","#galerias");</script>');
		HTML::Imprime('<script>Abrir("#abreGaleriasForm","#galeriasForm");</script>');
	}
	
	public function galeriasListar_action()
	{
		$bd = new Galerias_Model();
		$limite = 5;
		$pagina = abs((int)$this->getParam(3));
		$dados['pagina'] = $pagina;
		$pagina = $pagina*$limite;
		$dados['galerias'] = $bd->read("usuario={$this->_usuario}",$limite,$pagina,'id DESC');
		$dados['total'] = count($dados['postagens']);
		$dados['limite'] = $limite;
		
		$this->view('admin/galeriasListarAdmin',$dados);
		
	}
	
	public function galeriasImagensListar_action()
	{
		$galeria = abs((int) $this->getParam(3));
		$bd = new GaleriasImagens_Model();
		$dados['imagens'] = $bd->read("galeria={$galeria}");
		$this->view('admin/galeriasImagensListarAdmin',$dados);
		
	}
	
	public function galeriasUpload_action()
	{
		$galeriaId = abs((int) $_POST['galeria']);
		$targetFolder = 'uploads/'; // root framework
		
		$verifyToken = md5('unique_salt' . $_POST['timestamp']);
		
		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $targetFolder;
			$arquivoNome = rand(999, 9999999) . '_' . $_FILES['Filedata']['name'];
			$targetFile = rtrim($targetPath,'/') . '/' . $arquivoNome;
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				$img = new canvas();
				
				$img->load($targetFile)->resize('800','600')->save($targetPath . 'img_' . $arquivoNome);
				$img->load($targetFile)->resize('200','200')->save($targetPath.'tb_'.$arquivoNome);
				$img->load($targetFile)->resize('890','300')->save($targetPath.'destaque_'.$arquivoNome);
				unlink($targetFile);
				$galeria = new GaleriasImagens_Model();
				$galeria->insert(array('imagem' => $arquivoNome, 'usuario'=>$this->_usuario, 'galeria'=>$galeriaId));
				HTML::certo('A imagem '.$_FILES['Filedata']['name'].' foi enviada!');
			} else {
				HTML::erro('Não foi possível enviar a imagem '.$_FILES['Filedata']['name'].'!');
			}
		}
	}
	/**
	 * Galeria Imagem Exluir
	 * =============
	 * Exclui uma imagem da galeria
	 */
	public function galeriasExcluir_action()
	{
		$bd = new GaleriasImagens_Model();
		$id = abs((int) $this->getParam(2));
		$resultado = $bd->read("id={$id} AND usuario={$this->_usuario}");
		unlink('uploads/img_'.$resultado[0]['imagem']);
		unlink('uploads/tb_'.$resultado[0]['imagem']);
		unlink('uploads/destaque_'.$resultado[0]['imagem']);
		$bd->delete("id={$id} AND usuario={$this->_usuario}");
		HTML::certo('Imagem exclu&iacute;da.');
		//POG Temporário
		HTML::Imprime('<script>Abrir("#abreImagemGaleria","#imagens");</script>');
	}
	
	/**
	 * Galeria Exluir
	 * =============
	 * Exclui uma galeria e as imagens referenciadas a ela
	 */

	public function galeriaExcluir_action()
	{
		$bd = new GaleriasImagens_Model();
		$id = abs((int) $this->getParam(3));
		$resultado = $bd->read("galeria={$id} AND usuario={$this->_usuario}");
		foreach($resultado as $dados){
			unlink('uploads/img_'.$dados['imagem']);
			unlink('uploads/tb_'.$dados['imagem']);
			unlink('uploads/destaque_'.$dados['imagem']);
		}
		$bd->delete("galeria={$id} AND usuario={$this->_usuario}");
		
		$bd = new Galerias_Model();
		$bd->delete("id={$id} AND usuario={$this->_usuario}");
		
		HTML::certo('Galeria exclu&iacute;da.');
		//POG Temporário
		HTML::Imprime('<script>Abrir("#abreGaleriasListar","#galerias");</script>');
	}

	public function galeriaEditar_action()
	{
		$bd = new Galeria_Model();
		$id = abs((int) $this->getParam(2));
		$dados['imagens'] = $bd->read("id={$id}");
		$this->view('galeriaEditarAdmin',$dados);
		
	}
	
	public function galeriaEditarSalvar_action()
	{
		$galeria = new Galeria_Model();
		$id = abs((int) $_POST['modificar']);
		$_POST['destaque'] = abs((int) $_POST['destaque']);
		unset($_POST['modificar'],$_POST['enviar']);
		$galeria->update($_POST,"id={$id}");
		HTML::certo('Imagem atualizada.');
		//POG Temporário
		HTML::Imprime('<script>Abrir("#abreGaleria","#galeria");</script>');
	}
	
	public function template_action()
	{
		$template = new Template_Model();
		unset($_POST['modificar'],$_POST['enviar']);
		$template->update($_POST);
		HTML::certo('Template atualizado.');
	}

	public function logo_action()
	{
		$targetFolder = 'uploads/'; // root framework
		
		
		
		if (!empty($_FILES) ) {
			$tempFile = $_FILES['logo']['tmp_name'];
			$targetPath = $targetFolder;
			$arquivoNome = rand(999, 9999999) . '_' . date("YmdHis");
			
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['logo']['name']);
			
			$arquivoNome = rand(999, 9999999) . '_' . date("YmdHis") . '.'  . $fileParts['extension'];
			
			$targetFile = rtrim($targetPath,'/') . '/' . $arquivoNome;
			
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				$img = new canvas();
				$img->load($targetFile)->resize('100','100')->save($targetPath.'tb_'.$arquivoNome);
				//unlink($targetFile);
				$db = new Template_Model();
				$ressultado = $db->read();
				unlink('uploads/'.$ressultado[0]['logo']);
				unlink('uploads/tb_'.$ressultado[0]['logo']);
				$db->update(array('logo' => $arquivoNome));
				HTML::Imprime('<img src="uploads/'.$arquivoNome.'" />');
			} else {
				HTML::erro('Não foi possível enviar a imagem!');
			}
		}
	}

	public function fundoUpload_action()
	{
		$targetFolder = 'uploads/'; // root framework
		
		
		
		if (!empty($_FILES) ) {
			$tempFile = $_FILES['backgroundImagem']['tmp_name'];
			$targetPath = $targetFolder;
			$arquivoNome = rand(999, 9999999) . '_' . date("YmdHis");
			
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['backgroundImagem']['name']);
			
			$arquivoNome = rand(999, 9999999) . '_' . date("YmdHis") . '.'  . $fileParts['extension'];
			
			$targetFile = rtrim($targetPath,'/') . '/' . 'fundo_'.$arquivoNome;
			
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				$img = new canvas();
				$img->load($targetFile)->resize('100','100')->save($targetPath.'tb_'.$arquivoNome);
				//unlink($targetFile);
				$db = new Template_Model();
				$ressultado = $db->read();
				unlink('uploads/fundo_'.$ressultado[0]['backgroundImagem']);
				unlink('uploads/tb_'.$ressultado[0]['backgroundImagem']);
				$db->update(array('backgroundImagem' => $arquivoNome));
				HTML::Imprime('<img src="uploads/fundo_'.$arquivoNome.'" />');
			} else {
				HTML::erro('Não foi possível enviar a imagem!');
			}
		}
	}

	// Textos
	public function textos_action()
	{
		$this->_title = 'Administrador - Textos Fixos';
		$dados['lugar'] = 'textos';
		$bd = new Paginas_Model();
		$dados['dados'] = $bd->read("usuario={$this->_usuario}");
		$this->template('admin/textosAdmin',$dados);
		
	}

	// Postagens
	public function postagens_action()
	{
		$this->_title = 'Administrador - Postagens';
		$dados['lugar'] = 'postagens';
		//$bd = new Paginas_Model();
		//$dados['dados'] = $bd->read();
		$this->template('admin/postagensAdmin',$dados);
		
	}
	
	public function postagensForm_action()
	{
		$id = abs((int)$this->getParam(3));
		if($id){
			$bd = new Postagens_Model();
			$dados['postagem'] = $bd->read("id=$id");
		} 
		$this->view('admin/postagensFormAdmin',$dados);
	}
	
	public function postagensSalvar_action()
	{
		$bd = new Postagens_Model();
		$id = abs((int) $_POST['modificar']);
		unset($_POST['modificar'],$_POST['enviar']);
		if($id){
			$bd->update($_POST,"id={$id}");
			HTML::certo('Postagem atualizada com sucesso.');
		} else {
			$_POST['data'] = date("Y-m-d H:i:s");
			$bd->insert($_POST);
			HTML::certo('Postagem criada com sucesso.');
		}
		
		//POG Temporário
		HTML::Imprime('<script>Abrir("#abrePostagensListar","#postagens");</script>');
		HTML::Imprime('<script>Abrir("#abrePostagensForm","#postagemForm");</script>');
	}
	
	public function postagensListar_action()
	{
		$bd = new Postagens_Model();
		$limite = 5;
		$pagina = abs((int)$this->getParam(3));
		$dados['pagina'] = $pagina;
		$pagina = $pagina*$limite;
		$dados['postagens'] = $bd->read(null,$limite,$pagina,'data DESC');
		$dados['total'] = count($dados['postagens']);
		$dados['limite'] = $limite;
		
		$this->view('admin/postagensListarAdmin',$dados);
		
	}
	
	public function postagensExcluir_action()
	{
		$id = abs((int) $this->getParam(3));
		if($id){
			$bd = new Postagens_Model();
			$bd->delete("id={$id}");
			HTML::certo("Postagem excluída!");
			HTML::Imprime('<script>Abrir("#abrePostagensListar","#postagens");</script>');
		}
	}

}

?>