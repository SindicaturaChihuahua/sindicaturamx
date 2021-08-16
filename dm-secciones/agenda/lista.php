<?php
$bodys->set('titulo', "#AgendaAbierta");
$bodys->setMeta('title', "#AgendaAbierta");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "agenda");
// Head
require_once( VIEWS .'head.php' );
?>

<div class="main">
    <div class="fullwrap spacing-small-top extra-spacing-bottom">
        <div class="wrap">
            <div class="auditorias-top">
                <h1 class="tcenter">#AgendaAbierta</h1>
                <p>Esta Sindicatura lo tiene claro: haremos todo lo que esté en nuestras manos para reconstruir la confianza que tienen las y los chihuahuenses hacia el servicio público. Por eso, hemos decidido transparentar la agenda del Síndico Amin Anchondo, para que las personas puedan conocer el trabajo que existe detrás de alguien que representa a las personas en el cabildo. <br><small>*Las actividades se actualizan con dos días de diferencia.</small></p>
            </div>
            <img class="decor decor04" src="<?=URLIMAGES;?>decor04.png" alt="">

            <div class="agenda-calendar">
                <iframe src="https://calendar.google.com/calendar/embed?src=comunicacionsindicatura18.21%40gmail.com&ctz=America%2FMazatlan" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
