<?php
include_once(DMCLASSES.'transparencia.php');
include_once(DMCLASSES.'transparenciaItem.php');
include_once(DMCLASSES.'transparenciaSlider.php');
include_once(DMCLASSES.'transparenciaSlide.php');
$bodys->set('titulo', "#Infoparapersonas");
$bodys->setMeta('title', "#Infoparapersonas");

//$bodys->set('titulo', "#Infoparatodas");
//$bodys->setMeta('title', "#Infoparatodas");

$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("bottomjs", URLPLUGINS."owl/owl.carousel.js");
$bodys->set("page", "transparencia");
// Head
require_once( VIEWS .'head.php');
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="auditorias-top">
                <h1 class="tcenter">#Infoparatodaslaspersonas</h1>
                <p>En esta sección podrás encontrar información relevante que queremos compartir desde la Sindicatura con todas las personas.</p>
            </div>
        </div>
    </div>
    <div class="fullwrap spacing-small">
        <div class="wrap wrapmed">
            <?php
            $slider = new TransparenciaSlider($db);
            $slider->get();
            $slider->printSlider();
            ?>
        </div>
    </div>
    <div class="fullwrap spacing no-top">
        <div class="wrap wrapbig">
            <div class="comision-miembros">
                <?php
                $transparencia = new Transparencia($db);
                $transparencia->printInformacionByCategoria();
                ?>
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
