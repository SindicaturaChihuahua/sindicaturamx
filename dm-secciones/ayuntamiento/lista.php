<?php
$bodys->set('titulo', "Tu Ayuntamiento");
$bodys->setMeta('title', "Tu Ayuntamiento");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "ayuntamiento");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="consejo-top">
                <div class="margin-wrap">
                    <div class="consejo-top-logo">
                        <h1>Tu Ayuntamiento</h1>
                    </div>
                    <div class="consejo-top-img">
                        <img src="<?=URLIMAGES;?>decor07.png" alt="">
                    </div>
                </div>
            </div>
            <div class="margin-wrap">
                <div class="consejo-top-txt">
                    <p>En la Sindicatura de Chihuahua creemos que para hacer del servicio público un sinónimo de excelencia se tienen que atender las exigencias de las personas e impulsar las prácticas que nacen desde la sociedad civil organizada para mejorar los gobiernos.</p>
                    <p>Es por esto que nos hemos dado a la tarea de dar puntual seguimiento y transparentar de la manera más sencilla posible el trabajo del Ayuntamiento de Chihuahua; por lo que hemos reproducido el esfuerzo que hace RegidorMX para dar a conocer la importante labor que hacen las regidoras y regidores de nuestro municipio.</p>
                </div>
                <div class="consejo-top-txt">
                    <p>En esta sección podrás encontrar quienes son las y los regidores de Chihuahua, sus datos de contacto, su declaración 3 de 3, la comisiones en las que participan, las sesiones de las comisiones con algunos de sus documentos de trabajo e incluso las sesiones de Cabildo. </p>
                    <a class="consejo-regidores" href="https://regidor.mx/" target="_blank" title="RegidorMX">
                        <div class="margin-wrap">
                            <div class="consejo-regidores-img">
                                <img class="regidores-logo" src="<?=URLIMAGES;?>regidormx.png" alt="Ayuntamiento - RegidorMX">
                            </div>
                            <div class="consejo-regidores-txt">
                                <p>Reconocemos el esfuerzo y agradecemos el apoyo de la iniciativa RegidorMX, de quienes hemos replicado algunos esfuerzos en esta sección.</p>
                            </div>
                        </div>
                    </a>
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <div class="fullwrap spacing">
        <div class="wrap wrapbig">
            <div class="margin-wrap">
                <div class="ayuntamiento-box">
                    <a href="<?=URL;?>ayuntamiento/miembros" class="ayuntamiento-box-cover ab-miembros-bg">
                        <div class="ayuntamiento-box-txt">
                            <h1 class="text-white">Miembros</h1>
                            <div class="btn-secondary btn-secondary-white">Más información</div>
                        </div>
                        <img src="<?=URLIMAGES;?>ayuntamiento-miembros.png" class="ayuntamiento-box-img">
                    </a>
                </div>
                <div class="ayuntamiento-box">
                    <a href="<?=URL;?>ayuntamiento/calendario-de-regidores" class="ayuntamiento-box-cover ab-calendario-bg">
                        <div class="ayuntamiento-box-txt">
                            <h1 class="text-white">Calendario de regidores</h1>
                            <div class="btn-secondary btn-secondary-white">Más información</div>
                        </div>
                        <img src="<?=URLIMAGES;?>ayuntamiento-calendario.png" class="ayuntamiento-box-img">
                    </a>
                </div>
                <div class="ayuntamiento-box">
                    <a href="<?=URL;?>ayuntamiento/comisiones" class="ayuntamiento-box-cover ab-comisiones-bg">
                        <div class="ayuntamiento-box-txt">
                            <h1 class="text-white">Comisiones de regidores</h1>
                            <div class="btn-secondary btn-secondary-white">Más información</div>
                        </div>
                        <img src="<?=URLIMAGES;?>ayuntamiento-comisiones.png" class="ayuntamiento-box-img">
                    </a>
                </div>
                <div class="ayuntamiento-box">
                    <a href="<?=URL;?>ayuntamiento/sesiones-de-cabildo" class="ayuntamiento-box-cover ab-sesiones-bg">
                        <div class="ayuntamiento-box-txt">
                            <h1 class="text-white">Sesiones de cabildo</h1>
                            <div class="btn-secondary btn-secondary-white">Más información</div>
                        </div>
                        <img src="<?=URLIMAGES;?>ayuntamiento-sesiones.png" class="ayuntamiento-box-img">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
