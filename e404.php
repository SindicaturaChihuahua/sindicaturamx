<?php
header("HTTP/1.1 404 Not Found");
header('X-Robots-Tag: noindex, follow');
$bodys->setMeta("robots", "noindex");
require_once( VIEWS .'head.php' );
?>

<div class="templates">
    <div class="ticon"><i class="fa fa-fire-extinguisher"></i></div>
    <h3>Oops! Parece que lo perdimos.</h3>
    <h5>Lo sentimos la entrada que buscas no existe o fue removida de la plataforma.</h5>
</div>

<?php
require_once( VIEWS .'footer.php' );
?>
