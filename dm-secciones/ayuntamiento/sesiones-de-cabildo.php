<?php
include_once(DMCLASSES.'sesiones.php');
include_once(DMCLASSES.'sesion.php');
$bodys->set('titulo', "Sesiones de Cabildo | Ayuntamiento");
$bodys->setMeta('title', "Sesiones de Cabildo | Ayuntamiento");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.URL.'ayuntamiento/miembros">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "ayuntamiento-sesiones");
// Head
require_once( VIEWS .'head.php');
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="volver-atras">
                <a href="<?=URL;?>ayuntamiento">< Volver atrás</a>
            </div>
            <h2 class="tcenter">Sesiones de Cabildo</h2>
        </div>
    </div>
    <div class="fullwrap spacing no-top">
        <div class="wrap wrapbig">
            <div class="comision-miembros">
                <?php
                $sesiones = new Sesiones($db);
                if(isset($_GET["p"]) && !empty($_GET["p"])){
                    $page = $_GET["p"];
                }else{
                    $page = 1;
                }
                $limite = 25;
                $_GET['dm_currenturl'] = URL.$controlador->name.'/sesiones-de-cabildo?go=1';
                $total = $sesiones->getTotal();
                $totalSesiones = count($sesiones->sesiones);
                if($total > 0){
                    $sesiones->get($page,$limite);
                    $sesiones->printSesiones();
                }
                $sesiones->paginacion($total, $totalSesiones, $limite, $page);
                ?>
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
