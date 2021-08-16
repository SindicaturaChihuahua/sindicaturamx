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

An uncaught Exception was encountered

Type: <?php echo get_class($exception); ?>
Message: <?php echo $message; ?>
Filename: <?php echo $exception->getFile(); ?>
Line Number: <?php echo $exception->getLine(); ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

Backtrace:
	<?php foreach ($exception->getTrace() as $error): ?>
		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

	File: <?php echo $error['file']; ?>
	Line: <?php echo $error['line']; ?>
	Function: <?php echo $error['function']; ?>

		<?php endif ?>

	<?php endforeach ?>
<?php endif ?>

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