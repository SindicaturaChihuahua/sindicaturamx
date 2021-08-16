if (!RedactorPlugins) var RedactorPlugins = {};

(function($)
{
	RedactorPlugins.insertimage = function()
	{
		return {
			getTemplate: function()
			{
				return String()
				+ '<section id="redactor-modal-insertimage-insert">'
					+ '<label>Enlace a la imagen</label>'
					+ '<input type="text" id="redactor-insert-insertimage-area" value="" />'
					+ addinsert
				+ '</section>';
			},
			init: function()
			{
				$directinsertimage = $("#directinsertimage");
				addinsert = '';
				if($directinsertimage[0]){
					addinsert = $directinsertimage.html();
				}
				var button = this.button.addAfter('image', 'insertimage', 'Insertar imagen...');
				this.button.addCallback(button, this.insertimage.show);
			},
			show: function()
			{
				this.modal.addTemplate('insertimage', this.insertimage.getTemplate());
				this.modal.load('insertimage', 'Insertar imagen...', 700);
				this.modal.createCancelButton();

				var button = this.modal.createActionButton(this.lang.get('insert'));
				button.on('click', this.insertimage.insert);

				this.selection.save();
				this.modal.show();

				if(addinsert!=''){
					var $ufiles = $('#redactor-modal div.fuploadfiles');
					if($ufiles[0]){
						var $cargabarra = $ufiles.find('.carga-barra');
						var $cargastatus = $ufiles.find('.carga-status');
						var sendto = $ufiles.data('url');
						var estilo = $ufiles.data('estilo');
						var id = $ufiles.data('id');
						if(sendto){
							var $fileuploadbtn = $('#redactor-modal .fileupload');
							$fileuploadbtn.fileupload({
								dataType: 'json',
								url: sendto,
								formData: {"id": id, "estilo": estilo},
								add: function (e, data) {
									$(this).attr("disabled", "disabled");
									data.submit();
								},
								done: function (e, data) {
									if(data.result.errores>0){
										launchToastr('error',data.result.message);
									}else if(data.result.file){
										$('#redactor-insert-insertimage-area').val(data.result.file);
										launchToastr('success','Recurso cargado exitosamente');
										$(this).removeAttr('disabled');
									}
									$cargabarra.css('width', '0%');
									$cargastatus.html('');
								},
								fail: function (e, data) {
									$(this).removeAttr('disabled');
								},
								progressall: function (e, data) {
									var progress = parseInt(data.loaded / data.total * 100, 10);
									$cargabarra.css('width', progress + '%');
									if(progress==100){
										$cargastatus.html('Procesando...');
									}
								},
								stop: function (e){

								}
							}).on('fileuploadsubmit', function (e, data) {
								
							});

							$('#redactor-modal .fileupload').find('.fileupload').fileupload(
								'option',
								'redirect',
								window.location.href.replace(
									/\/[^\/]*$/,
									'/cors/result.html?%s'
								)
				    		);
						}
					}
				}
				
				$('#redactor-insert-insertimage-area').focus();

			},
			insert: function()
			{
				var tipo = 'image';
				var data = $('#redactor-insert-insertimage-area').val();
				data = this.clean.stripTags(data);

				if(data.match(/jpg|png|jpeg|jpe|gif/gi)){
					data = '<img src="'+ data +'" />';
				}else{
					data = '<a href="'+ data +'" target="_blank">'+ data +'</a>';
					tipo = 'archivo';
				}

				this.selection.restore();
				this.modal.close();

				var current = this.selection.getBlock() || this.selection.getCurrent();

				if (current) $(current).after(data);
				else
				{
					this.insert.html(data);
				}

				this.code.sync();
			}

		};
	};
})(jQuery);