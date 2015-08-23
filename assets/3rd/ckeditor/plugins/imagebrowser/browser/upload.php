<?php  

		require_once 'canvas.php';

		$targetFolder = '../../../../../../uploads/media/';
		
		
		
		if (!empty($_FILES) ) {
			$tempFile = $_FILES['imagem']['tmp_name'];
			$targetPath = $targetFolder;
			$arquivoNome = rand(999, 9999999) . '_' . date("YmdHis");
			
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['imagem']['name']);
			
			$arquivoNome = rand(999, 9999999) . '_' . date("YmdHis") . '.'  . $fileParts['extension'];
			
			$targetFile = rtrim($targetPath,'/') . '/' . $arquivoNome;
			
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				$img = new canvas();
				if(method_exists($img, 'resize')){
					$img->load($targetFile)->resize('100','100')->save($targetPath.'tb_'.$arquivoNome);
				} else {
					echo 'Função canvas não encontrada';
				}
			} else {
				echo 'Não foi possível enviar a imagem!';
			}
		}

?>