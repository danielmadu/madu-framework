<?php  

class URL {
	
	public static function redirect($destino)
	{
		header("Location:{$destino}");
	}
}


?>