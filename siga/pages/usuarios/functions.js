!function($){
	"use strict";
	
	var $checkboxes = $("#permisos .checkbox .check");
	var $checkboxeselm = $("#permisos .checkbox");
	
	$("body").on("beforeObjectFormSerialize", function(){
		$checkboxes.removeAttr("disabled");
	});
	$("body").on("afterObjectFormSerialize", function(){
		checkCheckBoxes();
	});
		
	$checkboxes.change(function() {
		//var clickeado = $(this).closest('.checkbox').data("permiso");
		checkCheckBoxes();   
	});
	
	function checkCheckBoxes(){
		var obligatorios = new Array();
		$checkboxeselm.each(function(index, element) {
			if($checkboxes[index].checked){
				var r, o;
				if(o = $(this).data('obligatorios')){
					var tmp2 = o.split(",");
					obligatorios = obligatorios.concat(tmp2);
				}
			}
        });
		obligatorios = arrayUnique(obligatorios);
		
		$checkboxes.removeAttr("disabled");
		for(var i=0; i<obligatorios.length; ++i) {
			$("#permisos ."+obligatorios[i]+" .check").eq(0).prop("checked", true).attr("disabled", "disabled");
		}
	}
	checkCheckBoxes();
	
}(window.jQuery);