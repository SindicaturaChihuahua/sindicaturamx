$.ajaxSetup({cache:false});
var DMGConfig = {};
DMGConfig.fb_appid = '';
DMGConfig.rootpath = "/";

var $objectform, $objectImage, $objectImageZone, $editores;
var $toastlast;
var dmdebug=false;

$(document).ready(function(e) {
    setConfig();
 	setMenu();
	initObjectForm();
    initReOrderTable();
	initToastr();
	initOthers();
});

function setConfig(){
	DMGConfig.publicAjax = DMGConfig.rootpath+"public/ajax/";
	DMGConfig.publicApi = DMGConfig.rootpath+"api/";
	DMGConfig.pluginspath = DMGConfig.rootpath+"public/plugins/";
	DMGConfig.loggedin = false;
	DMGConfig.url = window.location.origin + DMGConfig.rootpath;
	DMGConfig.redirecting = false;
    DMGConfig.siga = DMGConfig.rootpath+"siga/";
    DMGConfig.sigaAjax = DMGConfig.rootpath+"siga/ajax/";
}

function setMenu(){
	var $active = $("#menu").find('.tabcontainer .tcontent a.active');
	if($active[0]){
		$active.closest('.tabcontainer').addClass('open active');
	}
}

function initOthers(){
	var tools = $('.bstooltip');
	if(tools[0]){
		$('.bstooltip').tooltip({
			container:'body'
		});
	}
    $("a.mostrar-informacion").click(function(e){
        e.preventDefault();
        var open = $(this).attr("href").replace(/#/, '');
        $("div#" + open).show();
        $(this).hide();
    });
}

function initToastr(){
	toastr.options = {
		closeButton: true,
		positionClass: 'toast-top-right',
		onclick: null
	};
}

function launchToastr(tipo,info,titulo){
	tipo = tipo ? tipo : 'info';
	info = info ? info : 'Revisa los datos';
	titulo = titulo ? titulo : '';
	var $toast = toastr[tipo](info,titulo);
    $toastlast = $toast;
}

function clearToastr(){
	toastr.clear();
}

function initObjectForm(){
	$objectform=$("form.object-ajax");
	$objectform.data('sending', false);
	$objectform.submit(function(e){
		e.preventDefault();
		var action = $objectform.attr('action') ? $objectform.attr('action') : '';
		if(!$objectform.data('sending') && action!=''){
			if(typeof CKEDITOR !== 'undefined'){
				for(var instanceName in CKEDITOR.instances) {
					CKEDITOR.instances[ instanceName ].updateElement();
				}
			}
			$("body").trigger("beforeObjectFormSerialize");
			var formData = $objectform.serialize();
			$("body").trigger("afterObjectFormSerialize");
			$objectform.data('sending',true);
			$("img.floading").fadeIn('fast');
			$objectform.find(".blockatsubmit").attr("disabled", "disabled");
			clearToastr();
			$.ajax({
				type: "POST",
				url: action,
				data: formData,
				dataType: "html"
			}).done(function(data, status, objXHR){
                console.log(data);
                data = $.parseJSON(data);
				if(data.errores>0){
					launchToastr('error',data.message);
				}else{
					launchToastr('success',data.message);
					if(data.reload!=false){
						redirect(data.reload,500);
					}
					$("body").trigger("ObjectFormDone",data);
				}
			}).fail(function(objXHR, status){
				launchToastr('warning','Error Code: 00091512');
			}).always(function(data){
				$("img.floading").fadeOut('fast');
				$objectform.find(".blockatsubmit").removeAttr('disabled');
				$objectform.data('sending',false);
			});
		}
	});

	$editores=$('.aneditor');
	if($editores[0]){
		if(typeof CKEDITOR !=='undefined'){
			CKEDITOR.replaceAll( function(textarea,config) {
				if (textarea.className!="aneditor") return false;
			});
		}
	}

	$('#modal-general').on('click','.delete-object-btn-confirm',function(e){
		e.preventDefault();
		confirmDeleteObject($(this).data('url'));
	});

	$objectform.on('click','.delete-object-btn', function(e){
		e.preventDefault();
		deleteObject($(this).attr('href'));
	});

}

function deleteObject(url){
	$('#modal-general').removeData('bs.modal');
	$('#modal-general').modal({remote:url});
}

function confirmDeleteObject(url){
	$('#modal-general').modal('hide');
	$.ajax({
		url: url,
		dataType: 'json'
	}).always(function () {
	}).done(function (data) {
		$("body").trigger("ConfirmDeleteDone",data);
		if(data.errores>0){
			launchToastr('error',data.message);
		}else{
			launchToastr('success','Eliminado exitosamente');
			if(data.reload!=false){
				redirect(data.reload,1000);
			}
		}
	});
}

function initReOrderTable(){
	var $reordertables=$(".reordertable");
	if($reordertables[0]){
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};

		$reordertables.each(function(index, element) {
			var urltosave = $(this).data('url');
			$(this).sortable({
				handle: ".moveorder",
				placeholder: "rt-holder",
				helper: fixHelper,
				update: function( event, ui ) {
					var tmpdata = {};
					tmpdata.action="reorder";
					tmpdata.order=$(this).sortable("serialize");
					$.ajax({
						type: "POST",
						url: urltosave,
						data: tmpdata,
						dataType: "json"
					}).done(function(data, status, objXHR){
						if(data.errores>0){
							launchToastr('error',data.message);
						}else{
						}
					}).fail(function(objXHR, status){
						launchToastr('error',"Error #0000145");
					});
				}
			}).disableSelection();
        });
	}
}

function redirect(url,time){
	if(time==0){
		location.href=url;
	}else{
		setTimeout("location.href='"+url+"'", time);
	}
}

function arrayUnique(array) {
    var a = array.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
};

function redondearDosDecimales(value){
	var v = Math.round(value*100)/100;
	return v;
}

function mylog(msg){
	if (dmdebug) {
	  console.log(msg);
	}
}
