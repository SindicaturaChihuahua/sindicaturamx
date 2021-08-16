<?php
header("HTTP/1.1 404 Not Found");
require_once( VIEWS .'head.php' );
?>

<div class="templates">
    <div class="ticon"><i class="fa fa-lock"></i></div>
    <h3>Oops! ¿Qué es lo que estás buscando aquí?</h3>
    <h5>Parece que no tienes los permisos necesarios para acceder a este contenido.</h5>
</div>

<?php 
require_once( VIEWS .'footer.php' );
?>