<?php  
/**
 * 
 */
class HTML {
	
	public static function teste($a=null){
		echo $a;
	}
	
	public static function certo($msg){
		echo '<div class="alert alert-success alert-dismissable">
  			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  			<strong>Sucesso!</strong> '.$msg.'
		</div>';
	}
	
	public static function erro($msg){
		echo '<div class="alert alert-danger alert-dismissable">
  			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  			<strong>Erro!</strong> '.$msg.'
		</div>';
	}
		
	public static function Imprime($a=null){
		echo $a;
	}
}

?>