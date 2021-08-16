<?php
include_once(DMCLASSES.'indicadores.php');
include_once(DMCLASSES.'indicador.php');
include_once(DMCLASSES.'evaluaciones.php');
$bodys->set('titulo', "¿Cómo vamos Chihuahua?");
$bodys->setMeta('title', "¿Cómo vamos Chihuahua?");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "como-vamos");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small">
        <div class="wrap">
            <div class="auditorias-top">
                <h1 class="tcenter">¿Cómo vamos Chihuahua?</h1>
                <p>Uno de los esfuerzos más importantes que quiere encabezar esta Sindicatura es en materia de transparencia proactiva; y es través de esta sección que queremos explicar de la forma más sencilla posible aspectos muy importantes del gobierno municipal.</p>
                <p>Aquí podrás encontrar información de mucha relevancia para el municipio, como el desglose de compras y adquisiciones, los contratos de obra pública, el avance en el ejercicio del presupuesto del municipio y los asuntos tratados por el cabildo en materia de desarrollo urbano.</p>
                <p>Además, encontrarás las calificaciones de transparencia que hacen importantes organizaciones a nivel nacional a nuestro municipio como CIMTRA e IMCO.</p>
            </div>
            <img class="decor decor01-2" src="<?=URLIMAGES;?>decor01.png" alt="">
        </div>
    </div>
    <div class="fullwrap spacing-big-bottom">
        <div class="wrap">
            <?php
            $indicadores = new Indicadores($db);
            $indicadores->get();
            $indicadores->printIndicadores();
            ?>
        </div>
    </div>
    <div class="fullwrap spacing no-top">
        <div class="wrap">
            <h1 class="tcenter">Evaluaciones externas</h1>
            <div class="evaluaciones-wrap">
                <?php
                $evaluaciones = new Evaluaciones($db);
                $evaluaciones->get();
                $evaluaciones->printEvaluaciones();
                ?>
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
