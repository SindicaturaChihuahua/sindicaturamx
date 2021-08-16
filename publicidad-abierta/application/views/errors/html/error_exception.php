<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

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

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>An uncaught Exception was encountered</h4>

<p>Type: <?php echo get_class($exception); ?></p>
<p>Message: <?php echo $message; ?></p>
<p>Filename: <?php echo $exception->getFile(); ?></p>
<p>Line Number: <?php echo $exception->getLine(); ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach ($exception->getTrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			File: <?php echo $error['file']; ?><br />
			Line: <?php echo $error['line']; ?><br />
			Function: <?php echo $error['function']; ?>
			</p>
		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>

<?php if (defined('SHOW_ERROR_CUSTOM') && SHOW_ERROR_CUSTOM === TRUE): ?>
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