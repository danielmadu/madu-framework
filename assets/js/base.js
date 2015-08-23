		bkLib.onDomLoaded(function() {
			new nicEditor({
				iconsPath : './assets/nicEditorIcons.gif', 
				fullPanel : true
			}).panelInstance('sobre');
			
			new nicEditor({
				iconsPath : './assets/nicEditorIcons.gif', 
				fullPanel : true
			}).panelInstance('contato');
			
			new nicEditor({
				iconsPath : './assets/nicEditorIcons.gif', 
				fullPanel : true
			}).panelInstance('postagem');
			
			});
			
			function PostarNicEdit(e, retorno){
				$('textarea').each(function(){
				   var IDOfThisTextArea =   $(this).attr('id');
				   nicEditors.findEditor(IDOfThisTextArea).saveContent()
				});
				Postar(e, retorno);				
				return false;
			}
			
			function Postar(e, retorno){
				$(e).submit(function() { return false; } );
				$(retorno).html('<i>Carregando...</i>');
				var data = $(e).serialize();
				var origem = $(e).attr('action');
				
				//console.log(data);
				$.post(origem,data,function(res){
					$(retorno).html(res);
				});
				
				
				
				return false;
			}
			
			function Abrir(e, retorno){
				var origem = $(e).attr('href');
				$(retorno).html('<i>Carregando...</i>');
				$(retorno).load(origem);
				return false;
			}