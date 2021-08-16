!function($){
	"use strict";

	var tiempos=Array();
    var $dmssearch, dmssearch, dmssearch_url;
	var actividad;
	var simpleconfig = {
		toolbarGroups: [
			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
			{ name: 'paragraph',   groups: [ 'list', 'indent' ] },
			{ name: 'styles'},
			{ name: 'links'},
		],
		removeButtons: 'Underline,Subscript,Superscript,Styles,Font,Cut,Paste,Copy,FontSize,Youtube'
	};

	/* PASOS */
	var $elpasos, $elpasoscon, elpasoaction, $elpasos1, $elpasoscon1, elpasoaction1;
	var $cajaedicion, $cajaedicion1;
	$elpasos = $("#elpasos-0");
	$elpasoscon = $("#elpasoscon-0");
	$elpasos1 = $("#elpasos-1");
	$elpasoscon1 = $("#elpasoscon-1");

	$elpasos.on('click','a.addaction', function(e){
		e.preventDefault();
		createPagoObject();
	});
	$elpasos1.on('click','a.addaction', function(e){
		e.preventDefault();
		createPagoObject1();
	});

	function createPagoObject(){
		if(!$elpasoscon.hasClass("creando")){
			$elpasoscon.addClass("creando");
			$.ajax({
				type: "POST",
				url: elpasoaction,
				dataType: "html"
			}).done(function(data, status, objXHR){
				if(data=="#000"){
					launchToastr('error',"Error de creacion");
				}else{
					$elpasoscon.append(data);
					init();
				}
			}).fail(function(objXHR, status){
				launchToastr('warning','Error al crear');
			}).always(function(data){
				$elpasoscon.removeClass("creando");
			});
		}
	}
	function createPagoObject1(){
		if(!$elpasoscon1.hasClass("creando")){
			$elpasoscon1.addClass("creando");
			$.ajax({
				type: "POST",
				url: elpasoaction1,
				dataType: "html"
			}).done(function(data, status, objXHR){
				if(data=="#000"){
					launchToastr('error',"Error de creacion");
				}else{
					$elpasoscon1.append(data);
					init();
				}
			}).fail(function(objXHR, status){
				launchToastr('warning','Error al crear');
			}).always(function(data){
				$elpasoscon1.removeClass("creando");
			});
		}
	}

	function init(){
		$('.single-selector-new').selectize({
            persist: false,
            create: false
        });
        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
		$('.timepicker').timepicker({
        });
		$('.multiple-selector').selectize({
			maxItems: 20,
			persist: false,
			createOnBlur: true,
			create: true
		});
		$cajaedicion=$('.cajaedicion');
		if($cajaedicion[0]){
			$cajaedicion.each( function( index, element ){
			    var id = $(this).attr("id");
				if(!CKEDITOR.instances[id]){
					CKEDITOR.replace(id, simpleconfig);
				}
			});
		}
	}

	function orden(){
		$(".sortable").sortable({
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

	function clearEquipoOptions(){
		var $s = $("select.dm-ms-search");
		$s.each(function(){
			$(this)[0].selectize.clearOptions();
		});
	}

	$("body").on("ConfirmDeleteDone", function(e, data){
		if(data.message=="#elpasoeliminada"){
			$("#elpasoobj-"+data.rawdata).slideUp("fast",function(){
				$(this).remove();
			});
		}
	});

	$(document).ready(function(e) {
		elpasoaction = $elpasos.data('action');
		elpasoaction1 = $elpasos1.data('action');
		orden();
		init();
    });

}(window.jQuery);
