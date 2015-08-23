<?php  
$pasta = '../../../../../../uploads/media/';
$excluir = $_GET['excluir'];
if($excluir){
	unlink($pasta.$excluir);
	unlink($pasta.'tb_'.$excluir);
}
?>
<script>location.reload();</script>