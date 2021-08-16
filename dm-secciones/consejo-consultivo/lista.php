<?php
include_once(DMCLASSES.'consejo.php');
include_once(DMCLASSES.'consejoMiembro.php');
$bodys->set('titulo', "Consejo Consultivo de la Sindicatura de Chihuahua");
$bodys->setMeta('title', "Consejo Consultivo de la Sindicatura de Chihuahua");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "consejo-consultivo");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="consejo-top">
                <div class="margin-wrap">
                    <div class="consejo-top-logo">
                        <img src="<?=URLIMAGES;?>ccs-logo.png" alt="Consejo Consultivo Sindicatura">
                    </div>
                    <div class="consejo-top-img">
                        <img src="<?=URLIMAGES;?>decor07.png" alt="">
                    </div>
                </div>
            </div>
            <div class="margin-wrap">
                <div class="consejo-top-txt">
                    <p>El Consejo Consultivo de la Sindicatura es un órgano ciudadano de consulta que vigila, acompaña, da seguimiento y retroalimenta todas las acciones que llevamos a cabo desde la Sindicatura.</p>
                </div>
                <div class="consejo-top-txt">
                    <p>A través de una convocatoria abierta seleccionamos a perfiles con experiencia en materia de participación ciudadana, auditoría, mejora regulatoria, transparencia, rendición de cuentas y obra pública. Con esto, nos convertimos en la primer Sindicatura en el país, que bajo el esquema de Consejo Consultivo, revisará de forma colectiva y colaborativa las áreas más importantes del municipio.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="fullwrap spacing">
        <div class="wrap wrapbig">
            <?php
            $consejo = new Consejo($db);
            $consejo->get();
            $consejo->printMiembros();
            ?>
        </div>
    </div>
    <div class="fullwrap spacing no-top">
        <div class="wrap wrapbig">
            <div class="margin-wrap image-first">
                <div class="nosotros-principios-txt">
                    <div class="nosotros-principios-txt-wrap">
                        <h1>¿Por qué necesitamos un Consejo Consultivo?</h1>
                        <p>Porque queremos demostrar que SÍ hay nuevas formas de hacer bien las cosas en el servicio público.</p>
                        <p>Los retos que tenemos en transparencia, vigilancia de recursos públicos y combate a la corrupción son gigantescos. Y en esta Sindicatura sabemos solo se puede hacer frente a ellos  si estamos acompañados y trabajamos en conjunto con la sociedad civil que por años tiene empujando estos temas, para lograr un Chihuahua que gobierne para todas las personas. </p>
                    </div>
                </div>
                <div class="nosotros-principios-img">
                    <img src="<?=URLIMAGES;?>consejo-porque.jpg" alt="Sindicatura - Consejo Consultivo">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="nodisplay">
<?php
$consejo->printModalMiembros();
?>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
