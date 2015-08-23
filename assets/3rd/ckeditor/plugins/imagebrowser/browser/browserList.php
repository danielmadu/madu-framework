<?php
header("content-type: application/json");
$pasta = '../../../../../../uploads/media/';
$url = $_GET['pasta'];
//echo $url;
//echo $_SERVER['SCRIPT_FILENAME'];

 $lista = array();

if(is_dir($pasta)){
	$diretorio = dir($pasta);
	while($arquivo = $diretorio->read()){
		if($arquivo!='..' && $arquivo!='.' && strpos($arquivo, 'tb_')===false){
			$lista[] = array('image'=>$url.$arquivo,'thumb'=>$url.'tb_'.$arquivo);
		}
	}
	$diretorio->close();
}
 
 echo json_encode($lista);

?>