<?php
include_once(DMCLASSES.'calendario.php');
include_once(DMCLASSES.'calendarioItem.php');
$eventos = new Eventos($db);
if(isset($_GET["ver"]) && !empty($_GET["ver"])){
    $evC = $eventos->getCategoria($_GET["ver"]);
    $categoria = $evC["categoria_id"];
}else{
    $categoria = false;
}
$a = false;
$m = false;
if(isset($_GET["a"]) && !empty($_GET["a"])){
    $a = $_GET["a"];
}
if(isset($_GET["m"]) && !empty($_GET["m"])){
    $m = $_GET["m"];
}
$eventos->init($a,$m);
$_GET['dm_currenturl'] = URL.$controlador->name.'?go=1';
$bodys->set('titulo', "Calendario de regidores");
$bodys->setMeta('title', "Calendario de regidores");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "ayuntamiento-calendario");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="volver-atras">
                <a href="<?=URL;?>ayuntamiento">< Volver atrás</a>
            </div>
            <h2 class="tcenter">Calendario de regidores</h2>
        </div>
    </div>
    <div class="fullwrap spacing spacing-small-top">
        <div class="wrap">
            <?php
            $eventos->printEventosHeader();
            if(isset($_GET["p"]) && !empty($_GET["p"])){
                $page = $_GET["p"];
            }else{
                $page = 1;
            }
            $limite = 9999;
            $total = $eventos->getTotal($eventos->inicioMes, $eventos->finalMes, $categoria);
            $totalEventos = count($eventos->eventos);
            if($total > 0){
                $eventos->getCalendario($page,$limite,$eventos->inicioMes,$eventos->finalMes,$categoria);
                $eventos->printEventos();
            }else{
                echo '<p class="tcenter">No se han encontrado eventos para la fecha seleccionada, por favor elige otra.</p>';
            }
            // $eventos->paginacion($total, $totalEventos, $limite, $page);
            ?>
        </div>
    </div>
</div>
<script>
	var hF = <?=$eventos->hayfuturos;?>;
	var hP = <?=$eventos->haypasados;?>;
</script>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
