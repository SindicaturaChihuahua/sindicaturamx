<?php
include_once(DMCLASSES.'comisiones.php');
include_once(DMCLASSES.'comision.php');
$bodys->set('titulo', "Comisiones | Ayuntamiento");
$bodys->setMeta('title', "Comisiones | Ayuntamiento");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.URL.'ayuntamiento/comisiones">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "ayuntamiento-comisiones");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="volver-atras">
                <a href="<?=URL;?>ayuntamiento">< Volver atrás</a>
            </div>
            <div class="auditorias-top">
                <h2 class="tcenter">Comisiones de regidores</h2>
                <p>En Chihuahua, los Ayuntamientos trabajan por medio de comisiones, estas son creadas por acuerdo de los Ayuntamientos al inicio de cada administración y se integran por regidoras y regidores de los diferentes grupos políticos.</p>
                <p>La tarea de las comisiones de regidores es estudiar, analizar y discutir las iniciativas, proyectos y en general, cualquier asunto municipal, que tenga relación con la materia propia de su denominación, para luego someter a votación del pleno del Ayuntamiento dichos análisis o dictámenes.</p>
                <p>En nuestro municipio, estas comisiones son abiertas y públicas, es decir todas las personas pueden asistir a sus sesiones, escuchar las discusiones e intervenir en su caso, además las y los regidores tienen la obligación de transmitir dichas sesiones en vivo a través de los medios electrónicos del gobierno municipal.</p>
            </div>
            <img class="decor decor01-2" src="<?=URLIMAGES;?>decor01.png" alt="">
        </div>
        <img class="decor decor05" src="<?=URLIMAGES;?>decor05.png" alt="">
    </div>
    <div class="fullwrap spacing">
        <div class="wrap wrapbig">
            <div class="margin-wrap">
                <?php
                $comisiones = new Comisiones($db);
                $comisiones->get();
                $comisiones->printComisiones();
                ?>
            </div>
        </div>
    </div>

</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
