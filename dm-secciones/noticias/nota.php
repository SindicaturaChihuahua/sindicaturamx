<?php
include_once(DMCLASSES.'notas.php');
include_once(DMCLASSES.'nota.php');
$slug = $_GET["beta"];
$nota = new Nota($db);
$nota->getNota($slug);
if(!$nota->isOpen()){
    include("e404.php");
    exit;
}
$nota->addView();
$bodys->set('titulo', $nota->titulo.' | Noticias');
$bodys->setMeta('title', $nota->titulo.' | Noticias');
if(empty($nota->data["seo_description"])){
    $bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
}else{
    $bodys->setMeta('description', $nota->data["seo_descripcion"]);
}
if(empty($nota->extra["seo_tags"])){
    $bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
}else{
    $bodys->setMeta('keywords', $nota->extra["seo_tags"]);
}
$bodys->setMeta('canonical','<link rel="canonical" href="'.$nota->url.'">');
$bodys->setMeta('image', $nota->data["cover_image_big"]);
$bodys->set("bottomjs", URLPLUGINS."owl/owl.carousel.js");
$bodys->set("bottomjs", "//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5c6736b0f7d80ff3");
$bodys->set("page", "noticias");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small-top extra-spacing-bottom">
        <div class="wrap wrapsmall">
            <div class="volver-atras">
                <a href="<?=URL;?>noticias">< Volver atrás</a>
            </div>
            <div class="noticia-post">
                <?=$nota->printPortada();?>
                <div class="noticia-heading">
                    <span class="noticia-categoria"><?=$nota->categoria["categoria_nombre"];?></span>
                    <h2><?=$nota->titulo;?></h2>
                    <span class="noticia-fecha"><?=$nota->fecha;?></span>
                </div>
                <div class="noticia-txt">
                    <?=$nota->descripcion;?>
                    <img class="noticia-sello" src="<?=URLIMAGES;?>contacto-sello.png" alt="Sindicatura Chihuahua">
                </div>
                <div class="noticia-compartir">
                    Compartir esta noticia
                    <div class="compartir-redes">
                        <?=$nota->printSharer();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
