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
    <div class="wrap">
        <div class="margin-wrap">
            <div class="owl-carousel owl-theme owlHomeEnlaces">
                <div class="ohe-item">
                    <a href="<?=URL;?>transparencia" class="home-enlace-block heb-transparencia">
                        <div class="home-enlace-block-txt">
                            <h2>#Infoparatodas</h2>
                        </div>
                        <div class="home-enlace-block-hover">
                            <h2>#Infoparatodas</h2>
                            <p>En esta sección podrás encontrar información relevante que queremos compartir desde la Sindicatura con todas las personas.</p>
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
                            <p>Aquí podrás encontrar información relevante como el desglose de compras y adquisiciones, contratos de obra pública, presupuesto municipal, entre otros.</p>
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
                            <p>El Consejo Consultivo de la Sindicatura vigila, acompaña, da seguimiento y retroalimenta todas las acciones que llevamos a cabo desde la Sindicatura.</p>
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
                            <p>Conoce más sobre el Ayuntamiento de Chihuahua, sus miembros, las diferentes comisiones y sus respectivas sesiones de cabildo.</p>
                            <div class="btn-secondary btn-secondary-white">Más información</div>
                        </div>
                    </a>
                </div>
                <div class="ohe-item">
                    <a href="<?=URL;?>revisiones" class="home-enlace-block heb-auditorias">
                        <div class="home-enlace-block-txt">
                            <h2>Revisiones</h2>
                        </div>
                        <div class="home-enlace-block-hover">
                            <h2>Revisiones</h2>
                            <p>Avances y resultados de las revisiones y auditorías que llevamos a cabo a las diferentes áreas del gobierno municipal.</p>
                            <div class="btn-secondary btn-secondary-white">Más información</div>
                        </div>
                    </a>
                </div>
                <div class="ohe-item">
                    <a href="<?=URL;?>nosotros" class="home-enlace-block heb-nosotros">
                        <div class="home-enlace-block-txt">
                            <h2>¿Quiénes somos?</h2>
                        </div>
                        <div class="home-enlace-block-hover">
                            <h2>¿Quiénes somos?</h2>
                            <p>Avances y resultados de las revisiones y auditorías que llevamos a cabo a las diferentes áreas del gobierno municipal.</p>
                            <div class="btn-secondary btn-secondary-white">Más información</div>
                        </div>
                    </a>
                </div>
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
