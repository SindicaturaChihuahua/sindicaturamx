<?php
include_once(DMCLASSES.'comisiones.php');
include_once(DMCLASSES.'comision.php');
include_once(DMCLASSES.'comisionMiembros.php');
include_once(DMCLASSES.'comisionMiembro.php');
$slug = $_GET["gama"];
$comision = new Comision($db);
$comision->getComision($slug);
if(!$comision->isOpen()){
    include("e404.php");
    exit;
}
$comision->addView();
$bodys->set('titulo', $comision->titulo.' | Ayuntamiento');
$bodys->setMeta('title', $comision->titulo.' | Ayuntamiento');
if(empty($comision->data["seo_description"])){
    $bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
}else{
    $bodys->setMeta('description', $comision->data["seo_descripcion"]);
}
if(empty($comision->extra["seo_tags"])){
    $bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
}else{
    $bodys->setMeta('keywords', $comision->extra["seo_tags"]);
}
$bodys->setMeta('canonical','<link rel="canonical" href="'.$comision->url.'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "ayuntamiento-comisiones");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="volver-atras">
                <a href="<?=URL;?>ayuntamiento/comisiones">< Volver atrás</a>
            </div>
            <div class="comision-top">
                <img src="<?=$comision->data["cover_image_big"];?>" alt="<?=$comision->titulo;?>">
                <h2><?=$comision->titulo;?></h2>
                <div class="comision-top-txt">
                    <?=$comision->descripcion;?>
                </div>
            </div>
            <div class="comision-miembros">
                <?php
                $miembros = new ComisionMiembros($db);
                $miembros->getFromComision($comision->post_id);
                $miembros->printMiembros();
                ?>
            </div>
        </div>
    </div>
    <div class="fullwrap spacing">
        <div class="wrap wrapbig">
            <?php
            $comision->printSesiones();
            ?>
        </div>
    </div>
    <div class="fullwrap">
        <div class="wrap">
            <?php
            $comision->printNewsletter();
            ?>
        </div>
    </div>
</div>
<div class="nodisplay">
    <?php
    $miembros->printModalMiembros($comision->titulo);
    ?>
</div>

<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
