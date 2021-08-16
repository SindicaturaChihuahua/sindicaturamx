$.ajaxSetup({cache:false});
var wW,wH,sT;
var menumovilopen=false;
var dmdebug = false;
var resizeTimer, scrollTimer;
var page = "";

var DMGConfig = {};
DMGConfig.fb_appid = '';
DMGConfig.rootpath = "/";
DMGConfig.loggedin = false;

var $objectform, $objectImage, $objectImageZone, $editores;

$(document).ready(function(e){
	setConfig();
	initObjectForm();

	$(window).resize(function () {
		if (resizeTimer) {
			clearTimeout(resizeTimer); }
		resizeTimer = setTimeout(function() {
			resizeTimer = null; resizeWindow(); }, 200);
	});

	$(window).scroll(function () {
		if (scrollTimer) {
			clearTimeout(scrollTimer); }
		scrollTimer = setTimeout(function() {
			scrollTimer = null; scrollWindow(); }, 100);
	});

	resizeWindow();
	setNav();
});

function setConfig(){
	DMGConfig.publicAjax = DMGConfig.rootpath+"public/ajax/";
	DMGConfig.publicApi = DMGConfig.rootpath+"api/";
	DMGConfig.pluginspath = DMGConfig.rootpath+"public/plugins/";
	DMGConfig.url = window.location.origin + DMGConfig.rootpath;
	DMGConfig.urlcargas = DMGConfig.url+"public/cargas/";
	DMGConfig.redirecting = false;
}

function resizeWindow(){
	wW=$(window).width();
	wH=$(window).height();
	$("div.fullscreen").css({"height": wH+"px"});
	$("div.fullscreenfixed").css({"height": (wH-200)+"px"});
}

function scrollWindow(){
	sT=$(window).scrollTop();
	if(page=="home"){
		if(sT>=(420)){
			$("#header").addClass("header-fixed");
		}else{
			$("#header").removeClass("header-fixed");
		}
	}
}

function setNav(){
	page = $("#header").data("active");
	$(".m-"+page).addClass("menu-active");
	if(page !== "home" && page !== "page"){
		$("#header").addClass("header-fixed");
	}
	$("#header a.menubtn").click(function(e){
		e.preventDefault();
		toggleMenuMovil(!menumovilopen);
	});
}
function toggleMenuMovil(open){
	if($(window).width()<=1244){
		if(open){
			$("#header").toggleClass("open",true);
			menumovilopen=true;
		}else{
			$("#header").toggleClass("open",false);
			menumovilopen=false;
		}
	}
}

//InitObjectForm
function initObjectForm(){
	var $singleselectors = $('.single-selector');

	$objectform=$("form.object-ajax");
	if($objectform[0]){
		initToastr();
		$objectform.data('sending', false);
		$objectform.submit(function(e){
			var $this = $(this);
			e.preventDefault();
			var action = $this.attr('action') ? $this.attr('action') : '';
			if(!$this.data('sending') && action!=''){
				$("body").trigger("beforeObjectFormSerialize");
				var formData = $this.serialize();
				$("body").trigger("afterObjectFormSerialize");
				$this.data('sending',true);
				$("img.floading").fadeIn('fast');
				$this.find(".blockatsubmit").attr("disabled", "disabled");
				clearToastr();
				$.ajax({
					type: "POST",
					url: action,
					data: formData,
					dataType: "html"
				}).done(function(data, status, objXHR){
					console.log(data);
					data = $.parseJSON(data);
					if(typeof data.meta !== "undefined"){ data = data.meta; }
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
					$this.find(".blockatsubmit").removeAttr('disabled');
					$this.data('sending',false);
				});
			}
		});

		$('#modal-general').on('click','.delete-object-btn-confirm',function(e){
			e.preventDefault();
			confirmDeleteObject($(this).data('url'));
		});

		$objectform.on('click','.delete-object-btn', function(e){
			e.preventDefault();
			deleteObject($(this).attr('href'));
		});

		if($singleselectors[0]){
			$singleselectors.selectize({
	            persist: false,
	            create: false
	        });
		}
	}

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
		if(data.errores>0){
			launchToastr('error',data.message);
		}else{
			$("body").trigger("ConfirmDeleteDone",data);
			launchToastr('success','Eliminado exitosamente');
			if(data.reload!=false){
				redirect(data.reload,1000);
			}
		}
	});
}

function initToastr(){
	toastr.options = {
		closeButton: true,
		positionClass: 'toast-top-right',
		onclick: null
	};
}

function launchToastr(tipo, info, titulo){
	if(info!=""){
		tipo = tipo ? tipo : 'info';
		info = info ? info : 'Revisa los datos';
		titulo = titulo ? titulo : '';
		var $toast = toastr[tipo](info,titulo);
	    $toastlast = $toast;
	}
}

function clearToastr(){
	toastr.clear();
}

function mylog(msg){
	if (dmdebug && typeof console !== "undefined") {
	  console.log(msg);
	}
}

// FORM INPUTS
!function($){
	$(document).ready(function() {

		var input_selector = '.dmgroup .dminput, .dmgroup .dmtextarea';
		$(document).on('change', input_selector, function () {
			if($(this).val().length !== 0) {
				$(this).parent().addClass('lleno');
			}
		});

		$(input_selector).each(function(index, element) {
			if($(element).val().length > 0) {
				$(this).parent().addClass('lleno');
			}
		});

		$(document).on('focus', input_selector, function () {
			$(this).parent().addClass('activo');
		});

		$(document).on('blur', input_selector, function () {
			if ($(this).val().length === 0) {
				$(this).parent().removeClass('activo lleno');
			}else{
				$(this).parent().removeClass('activo');
			}
		});

	});
}(window.jQuery);

function redirect(url,time){
	DMGConfig.redirecting = true;
	if(time==0){
		location.href=url;
	}else{
		setTimeout("location.href='"+url+"'", time);
	}
}

function clear_form_elements($ele){
    $ele.find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });
}
