<?php
include_once(DMCLASSES.'comisiones.php');
include_once(DMCLASSES.'comision.php');
include_once(DMCLASSES.'comisionMiembros.php');
include_once(DMCLASSES.'comisionMiembro.php');
$bodys->set('titulo', "Miembros | Ayuntamiento");
$bodys->setMeta('title', "Miembros | Ayuntamiento");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.URL.'ayuntamiento/miembros">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "ayuntamiento-miembros");
// Head
require_once( VIEWS .'head.php');
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="volver-atras">
                <a href="<?=URL;?>ayuntamiento">< Volver atrás</a>
            </div>
            <h2 class="tcenter">Miembros del Ayuntamiento</h2>
        </div>
    </div>
    <div class="fullwrap">
        <div class="wrap wrapbig">
            <?php
            $destacados = new ComisionMiembros($db);
            $destacados->get("destacado");
            $destacados->printDestacados();
            ?>
        </div>
    </div>
    <div class="fullwrap spacing no-top">
		<h2 class="tcenter">Regidores</h2>
        <div class="wrap wrapbig">
            <div class="comision-miembros">
                <?php
                $miembros = new ComisionMiembros($db);
                $miembros->get("miembro");
                $miembros->printAllMiembros();
                ?>
            </div>
        </div>
    </div>
</div>
<div class="nodisplay">
    <?php
    $destacados->printModalDestacados();
    $miembros->printModalAllMiembros();
    ?>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
