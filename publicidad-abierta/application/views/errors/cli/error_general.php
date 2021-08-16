<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "\nERROR: ",
	$heading,
	"\n\n",
	$message,
	"\n\n";
	
	if (defined('SHOW_ERROR_CUSTOM') && SHOW_ERROR_CUSTOM === TRUE): ?>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>plugins/jQuery/jQuery-3.3.1.js"></script>
	<style>
	.divShowError{
		position: fixed;
		background: #ececec;
		width: 100%;
		height: 100%;
		top: 0px;
		z-index: 1031;
		left: 0;
		text-align: center;
	}

	.btn_reload{
		background: #3c8dbc;
		border: #3c8dbc;
		padding: 12px 20px;
		color: white;
		font-size: 16px;
		cursor: pointer;
	}
	</style>
	<div class="divShowError">
		<p style="font-size:3em;">Upppsss a ocurrido un error inesperado.</p>
		<button onClick="reload()" class="btn_reload">Volver a recargar la página</button>
	</div>
	
	<script>
		var reload = function(){
			location.reload();
		}
	</script>
	
	<?php endif ?>