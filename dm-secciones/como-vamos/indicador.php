<?php
include_once(DMCLASSES.'indicadores.php');
include_once(DMCLASSES.'indicador.php');
$slug = $_GET["beta"];
$indicador = new Indicador($db);
$indicador->getIndicador($slug);
if(!$indicador->isOpen()){
    include("e404.php");
    exit;
}
$indicador->addView();
$bodys->set('titulo', $indicador->titulo.' | ¿Cómo vamos?');
$bodys->setMeta('title', $indicador->titulo.' | ¿Cómo vamos?');
if(empty($indicador->data["seo_description"])){
    $bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
}else{
    $bodys->setMeta('description', $indicador->data["seo_descripcion"]);
}
if(empty($indicador->extra["seo_tags"])){
    $bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
}else{
    $bodys->setMeta('keywords', $indicador->extra["seo_tags"]);
}
$bodys->setMeta('canonical','<link rel="canonical" href="'.$indicador->url.'">');
$bodys->setMeta('image', $indicador->data["cover_image_big"]);
$bodys->set("bottomjs", 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js');
$bodys->set("page", "como-vamos");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing spacing-small-top">
        <div class="wrap">
            <div class="volver-atras">
                <a href="<?=URL;?>como-vamos">< Volver atrás</a>
            </div>
			<div class="indicador-top">
				<div class="auditorias-top">
	                <h2 class="tcenter"><?=$indicador->titulo;?></h2>
					<div class="indicador-txt">
						<?=$indicador->descripcion;?>
					</div>
					<div class="indicador-fecha">
						<?=$indicador->fechaCompleta;?>
					</div>
	            </div>
            </div>
            <div class="margin-wrap">
                <?php
				$indicador->printCover();
                $indicador->printArchivos();
                $indicador->printGraficas();
                ?>
            </div>
        </div>
    </div>
</div>

<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
