<?php
include_once(DMCLASSES.'homeSlider.php');
include_once(DMCLASSES.'homeSlide.php');
include_once(DMCLASSES.'carousel.php');
include_once(DMCLASSES.'carouselItem.php');
include_once(DMCLASSES.'notas.php');
include_once(DMCLASSES.'nota.php');
$bodys->set('titulo', "");
$bodys->setMeta('title', "");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "home");
$bodys->set("bottomjs", URLPLUGINS."owl/owl.carousel.js");
$bodys->set("bottomjs","//cdn.jsdelivr.net/npm/jquery.marquee@1.5.0/jquery.marquee.min.js");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="hero">
    <?php
    $hero = new homeSlider($db);
    $hero->get();
    $hero->printSlider();
    ?>
</div>
<div class="home-top-noticias">
    <div class="wrap">
        <?php
        $cintillo = new Notas($db);
        $page = 1;
        $limite = 6;
        $_GET['dm_currenturl'] = URL.$controlador->name.'?go=1';
        $total = $cintillo->getTotal(0);
        $totalNotas = count($cintillo->notas);
        if($total > 0){
            $cintillo->get($page,$limite,0);
            $cintillo->printNotasCintillo();
        }
        ?>
    </div>
</div>
<div class="fullwrap spacing no-bottom">
    <div class="wrap wrapmed">
        <?php
        $carousel = new Carousel($db);
        $carousel->get();
        $carousel->printSlider();
        ?>
    </div>
</div>
<?php
$destacados = new Notas($db);
$page = 1;
$limite = 1;
$_GET['dm_currenturl'] = URL.$controlador->name.'?go=1';
$totalD = $destacados->getTotal(1);
if($totalD > 0){
    echo '<div class="fullwrap spacing-big-top">
        <div class="wrap wrapbig">';
    $destacados->get($page,$limite,1);
    $destacados->printDestacados();
    echo '</div>
</div>';
}
?>
<div class="fullwrap spacing no-bottom">
    <div class="wrap wrapbig">
        <div class="owl-carousel owl-theme owlHomeEnlaces">
            <div class="ohe-item">
                <a href="<?=URL;?>transparencia" class="home-enlace-block heb-transparencia">
                    <div class="home-enlace-block-txt">
                        <h2>Transparencia</h2>
                    </div>
                    <div class="home-enlace-block-hover">
                        <h2>Transparencia</h2>
                        <p>A partir del segundo bimestre (Marzo-Abril) del 2018 arrancó el proyecto de transparencia en obras públicas del estado de Chihuahua.</p>
                        <div class="btn-secondary btn-secondary-white">Más información</div>
                    </div>
                </a>
            </div>
            <div class="ohe-item">
                <a href="<?=URL;?>como-vamos" class="home-enlace-block heb-como-vamos">
                    <div class="home-enlace-block-txt">
                        <h2>¿Cómo vamos?</h2>
                    </div>
                    <div class="home-enlace-block-hover">
                        <h2>¿Cómo vamos?</h2>
                        <p>A partir del segundo bimestre (Marzo-Abril) del 2018 arrancó el proyecto de transparencia en obras públicas del estado de Chihuahua.</p>
                        <div class="btn-secondary btn-secondary-white">Más información</div>
                    </div>
                </a>
            </div>
            <div class="ohe-item">
                <a href="<?=URL;?>consejo-consultivo" class="home-enlace-block heb-ccs">
                    <div class="home-enlace-block-txt">
                        <h2>CCS</h2>
                    </div>
                    <div class="home-enlace-block-hover">
                        <h2>CCS</h2>
                        <p>A partir del segundo bimestre (Marzo-Abril) del 2018 arrancó el proyecto de transparencia en obras públicas del estado de Chihuahua.</p>
                        <div class="btn-secondary btn-secondary-white">Más información</div>
                    </div>
                </a>
            </div>
            <div class="ohe-item">
                <a href="<?=URL;?>ayuntamiento" class="home-enlace-block heb-ayuntamiento">
                    <div class="home-enlace-block-txt">
                        <h2>Ayuntamiento</h2>
                    </div>
                    <div class="home-enlace-block-hover">
                        <h2>Ayuntamiento</h2>
                        <p>A partir del segundo bimestre (Marzo-Abril) del 2018 arrancó el proyecto de transparencia en obras públicas del estado de Chihuahua.</p>
                        <div class="btn-secondary btn-secondary-white">Más información</div>
                    </div>
                </a>
            </div>
            <div class="ohe-item">
                <a href="<?=URL;?>auditorias" class="home-enlace-block heb-auditorias">
                    <div class="home-enlace-block-txt">
                        <h2>Auditorías</h2>
                    </div>
                    <div class="home-enlace-block-hover">
                        <h2>Auditorías</h2>
                        <p>A partir del segundo bimestre (Marzo-Abril) del 2018 arrancó el proyecto de transparencia en obras públicas del estado de Chihuahua.</p>
                        <div class="btn-secondary btn-secondary-white">Más información</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="fullwrap spacing no-bottom">
    <div class="wrap">
        <div class="margin-wrap">
            <div class="home-enlaces">
                <a href="<?=URL;?>transparencia" class="home-enlace-box heb-small heb-transparencia">
                    <div class="home-enlace-txt">
                        <h1>Transparencia</h1>
                        <div class="btn-secondary btn-secondary-white">Más información</div>
                    </div>
                </a>
                <a href="<?=URL;?>nosotros" class="home-enlace-box heb-small heb-nosotros">
                    <div class="home-enlace-txt">
                        <h1>¿Quiénes somos?</h1>
                        <div class="btn-secondary btn-secondary-white">Más información</div>
                    </div>
                </a>
            </div>
            <div class="home-enlaces">
                <a href="<?=URL;?>ayuntamiento" class="home-enlace-box heb-ayuntamiento">
                    <div class="home-enlace-txt">
                        <h1>Ayuntamiento</h1>
                        <div class="btn-secondary btn-secondary-white">Más información</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="fullwrap spacing-big-top">
    <div class="wrap wrapbig">
        <div class="margin-wrap">
            <?php
            $noticias = new Notas($db);
            $page = 1;
            $limite = 3;
            $_GET['dm_currenturl'] = URL.$controlador->name.'?go=1';
            $total = $noticias->getTotal(0);
            $totalNotas = count($noticias->notas);
            if($total > 0){
                $noticias->get($page,$limite,0);
                $noticias->printNotasHome();
            }
            ?>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
