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
$bodys->set('titulo', $nota->titulo);
$bodys->setMeta('title', $nota->titulo);
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
if(empty($nota->data["cover_image_big"])){
    $bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
}else{
    $bodys->setMeta('image', $nota->data["cover_image_big"]);
}
$bodys->setMeta('canonical','<link rel="canonical" href="'.$nota->url.'">');
$bodys->set("page", "noticias");
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing no-top">
        <div class="wrap">
            <div class="volver-wrap">
                <a class="btn-volver" href="<?=URL;?>noticias">< Volver atrás</a>
            </div>
            <div class="noticia">
                <div class="noticia-cover" style="background-image:url(<?=$nota->data['cover_image_big'];?>);">
                    <span class="noticia-categoria"><?=$nota->categoria["categoria_nombre"];?></span>
                </div>
                <h2><?=$nota->titulo;?></h2>
                <div class="noticia-fecha"><?=$nota->fecha;?></div>
                <div class="noticia-contenido">
                    <?=$nota->descripcion;?>
                </div>
                <div class="noticia-compartir">
                    Compartir esta noticia
                    <div class="noticia-compartir-redes">
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
