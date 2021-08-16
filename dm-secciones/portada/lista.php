<?php
include_once(DMCLASSES.'homeSlider.php');
include_once(DMCLASSES.'homeSlide.php');
include_once(DMCLASSES.'carousel.php');
include_once(DMCLASSES.'carouselItem.php');
include_once(DMCLASSES.'notas.php');
include_once(DMCLASSES.'nota.php');
include_once(DMCLASSES.'seccionesSlider.php');
include_once(DMCLASSES.'seccionesSlide.php');
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
			<?php
		    $cSecciones = new seccionesSlider($db);
		    $cSecciones->get();
		    $cSecciones->printSlider();
		    ?>
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
