<?php
include_once(DMCLASSES.'miembros.php');
include_once(DMCLASSES.'miembro.php');
$bodys->set('titulo', "Nosotros");
$bodys->setMeta('title', "Nosotros");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("bottomjs", URLPLUGINS.'fancybox3/jquery.fancybox.min.js');
$bodys->set("page", "nosotros");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small">
        <div class="wrap wrapbig">
            <div class="margin-wrap">
                <div class="consejo-top-txt">
                    <h1>¿Qué es la Sindicatura?</h1>
                </div>
                <div class="consejo-top-txt">
                    <p>La Sindicatura de Chihuahua es la entidad que tiene a su cargo la vigilancia del patrimonio municipal. La tarea es muy grande y la responsabilidad es enorme considerando que Chihuahua es el único Estado del país en el que los síndicos son electos por el voto popular.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="fullwrap spacing">
        <div class="wrap">
            <img class="decor decor04" src="<?=URLIMAGES;?>decor04.png" alt="">
            <div class="margin-wrap">
                <div class="nosotros-que-txt">
                    <div class="nosotros-que-txt-wrap">
                        <h2>¿Qué hacemos en la Sindicatura?</h2>
                        <p>Vigilamos el correcto ejercicio del gasto y hacemos inspecciones, revisiones y auditorías a las diferentes áreas del municipio de Chihuahua.</p>
                    </div>
                </div>
                <div class="nosotros-que-img">
                    <img src="<?=URLIMAGES;?>nosotros-que.jpg" alt="Sindicatura - Nosotros">
                </div>
            </div>
        </div>
    </div>
    <div class="fullwrap spacing-small-top">
        <div class="wrap">
            <h2 class="nosotros-box-heading">¿Para qué nos esforzamos?</h2>
            <div class="margin-wrap">
                <div class="nosotros-box">
                    <img src="<?=URLIMAGES;?>nosotros-transparencia.png" alt="Transparencia">
                    Para construir una Sindicatura que sea sinónimo de excelencia, un referente local y nacional  en materia de transparencia proactiva, innovación política, mejora regulatoria y combate a la corrupción.
                </div>
                <div class="nosotros-box">
                    <img src="<?=URLIMAGES;?>nosotros-vigilante.png" alt="Vigilante">
                    Para ser un vigilante y colaborador de la administración municipal que anteponga el interés público en todas sus acciones y decisiones.
                </div>
                <div class="nosotros-box">
                    <img src="<?=URLIMAGES;?>nosotros-anticorrupcion.png" alt="Anticorrupción">
                    Para consolidar a la Sindicatura como una institución fiscalizadora que ayude a prevenir actos de corrupción y malas prácticas.
                </div>
                <div class="nosotros-box">
                    <img src="<?=URLIMAGES;?>nosotros-impulsar.png" alt="Impulsar">
                    Para que las acciones y políticas que impulsemos influyan y de ser necesario cambien la actitud de los servidores públicos del municipio de Chihuahua.
                </div>
                <div class="nosotros-box">
                    <img src="<?=URLIMAGES;?>nosotros-colaboracion.png" alt="Colaboración">
                    Para establecer un espacio permanente de colaboración y comunicación con la ciudadanía y las organizaciones de la sociedad civil.
                </div>
                <div class="nosotros-box">
                    <img src="<?=URLIMAGES;?>nosotros-comunicacion.png" alt="Comunicación">
                    Para crear un modelo de comunicación efectiva que haga accesibles y entendibles todas nuestra información, acciones y políticas.
                </div>
            </div>
        </div>
    </div>
    <div class="fullwrap spacing extra-spacing-bottom">
        <div class="wrap">
            <div class="margin-wrap image-first">
                <div class="nosotros-principios-txt">
                    <div class="nosotros-principios-txt-wrap">
                        <h2>Decálogo de principios</h2>
                        <p>Este decálogo es una guía ética bajo la cual trabajará esta Sindicatura los próximos tres años. Lo descrito aquí servirá como un marco de referencia para el actuar del Síndico Amin Anchondo y cada una de las personas que integran este equipo. </p>
                        <p><a data-fancybox="gallery" class="btn-primary" href="<?=URLIMAGES;?>decalogodeprincipios.jpg">Leer decálogo</a></p>
                    </div>
                </div>
                <div class="nosotros-principios-img">
                    <img src="<?=URLIMAGES;?>nosotros-principios.jpg" alt="Sindicatura - Declaración de principios">
                </div>
            </div>
        </div>
    </div>
    <?php /*
    <div class="fullwrap spacing extra-spacing-bottom">
        <div class="wrap">
            <div class="auditorias-top">
                <h2 class="tcenter">Agenda del Síndico</h2>
                <p>Esta es la agenda abierta de Amin. Aquí podrás saber con qué personas se reúne y cuáles son las actividades a través de las cuales buscamos construir un Chihuahua más transparente.<br>
                Todas las actividades son actualizadas con una diferencia de dos días.</p>
            </div>
            <img class="decor decor05" src="<?=URLIMAGES;?>decor05.png" alt="">
        </div>
    </div> */ ?>
    <div class="fullwrap bg-blue bg-equipo">
        <div class="wrap wrapsmall">
            <?php
            $equipo = new Miembros($db);
            $equipo->printMiembrosByCategoria();
            ?>
        </div>
    </div>
    <div class="fullwrap spacing spacing-big-top">
        <div class="wrap wrapbig">
            <div class="nosotros-contacto">
                <div class="nosotros-contacto-txt">
                    <p>Todas las personas que forman parte de la Sindicatura están totalmente comprometidas con los principios de un Gobierno Abierto: Transparencia, Participación y Colaboración. </p>
                    <p>En esta sección podrás encontrar los datos de contacto de todo el equipo. Sin embargo, si tienes alguna duda sobre nuestro trabajo puedes contactarnos aquí.</p>
                    <a class="btn-primary" href="<?=URL;?>contacto">Contacto</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="nodisplay">
<?php
$equipo->get();
$equipo->printModalMiembros();
?>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
