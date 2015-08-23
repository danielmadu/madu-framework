<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script>
	function Postar(e, retorno){
				$(e).submit(function() { return false; } );
				$(retorno).html('<i>Carregando...</i>');
				var data = $(e).serialize();
				var origem = $(e).attr('action');
				
				console.log(data);
				//$.post(origem,data,function(res){
				//	$(retorno).html(res);
				//});
				
				return false;
			}
</script>

<form action="admin/sobre" id="formContato" method="POST" onsubmit="return Postar(this,'#retornoSobre');">
	<textarea name="texto" style="width: 100%; height: 200px;">Testando</textarea>
	<input type="submit" value="Salvar" />
</form>