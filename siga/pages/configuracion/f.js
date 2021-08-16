!function($){
	"use strict";

	var $redes, $redescon, actionurl;
	$redes = $("#redes");
	$redescon = $("#redescon");

	$redes.on('click','a.addaction', function(e){
		e.preventDefault();
		createPagoObject();
	});
    $redescon.on('click','a.deleteaction', function(e){
		e.preventDefault();
        var $element = $(this).parent().parent();
        $element.slideUp("fast",function(){
            $(this).remove();
        });
	});

	function createPagoObject(){
        if(!$redescon.hasClass("creando")){
			$redescon.addClass("creando");
			$.ajax({
				type: "POST",
				url: actionurl,
				dataType: "html"
			}).done(function(data, status, objXHR){
				if(data=="#000"){
					launchToastr('error',"Error de creacion de red");
				}else{
					$redescon.append(data);
					reinit();
				}
			}).fail(function(objXHR, status){
				launchToastr('warning','Error al crear red');
			}).always(function(data){
				$redescon.removeClass("creando");
			});
		}
	}

	function reinit(){}

	function orden(){
		$redescon.sortable({
			handle: ".handle",
			placeholder: "fp-file-holder",
			start: function(event, ui){
                var textareaId = ui.item.find('textarea').attr('id');
                if (typeof textareaId != 'undefined') {
                    var editorInstance = CKEDITOR.instances[textareaId];
                    editorInstance.destroy();
                    CKEDITOR.remove( textareaId );
                }
            },
            stop: function(event, ui){
                var textareaId = ui.item.find('textarea').attr('id');
                if (typeof textareaId != 'undefined') {
					CKEDITOR.replace(textareaId, simpleconfig);
                }
            }
		});
	}

	$(document).ready(function(e) {
		orden();
		reinit();
        actionurl = $redes.data('actionurl');
    });

}(window.jQuery);
