$(document).ready(function(e) {
	var $nsortablecon = $('div.nsortablecon');
	if($nsortablecon[0]){
		$nsortablecon.each(function(index, element) {
			var $this = $(this);
			var urltosave = $(this).data('url');
			var $savebutton = $(this).find('a.nsortable-save-btn');
			var $ns = $(this).find('ol.active-nsortable');
			
			$this.data('sending', false);
			$ns.nestedSortable({
				forcePlaceholderSize: true,
				connectWith: "ol.inactive-nsortable",
				handle: 'div .handle',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				toleranceElement: '> div',
				maxLevels: 2,
				update: function( event, ui ) {
				}
			}).disableSelection();
			
			$("ol.inactive-nsortable").nestedSortable({
				forcePlaceholderSize: true,
				connectWith: "ol.nsortable",
				handle: 'div .handle',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				toleranceElement: '> div',
				maxLevels: 3,
				update: function( event, ui ) {
				}
			}).disableSelection();
			
			$savebutton.click(function(e){
				e.preventDefault();	
				if(!$this.data('sending')){
					var tmpdata = {};
					tmpdata.action="reorder";
					tmpdata.order=$ns.nestedSortable("serialize");
					$this.data('sending',true);
					$("img.floading").fadeIn('fast');
					$this.find(".blockatsubmit").attr("disabled", "disabled");
					clearToastr();
					$.ajax({
						type: "POST",
						url: urltosave,
						data: tmpdata,
						dataType: "json"
					}).done(function(data, status, objXHR){
						if(data.errores>0){
							launchToastr('error',data.message);
						}else{
							launchToastr('success',data.message);
						}
					}).fail(function(objXHR, status){ 
						launchToastr('warning','Error Code: 00011436');
					}).always(function(data){ 
						$("img.floading").fadeOut('fast');
						$this.find(".blockatsubmit").removeAttr('disabled');
						$this.data('sending',false);
					});
				}
			});
			
		});
	}
	
});