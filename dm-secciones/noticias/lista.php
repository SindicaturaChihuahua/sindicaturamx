<?php
include_once(DMCLASSES.'notas.php');
include_once(DMCLASSES.'nota.php');
include_once(DMCLASSES.'publicaciones.php');
include_once(DMCLASSES.'publicacion.php');
$bodys->set('titulo', "Noticias");
$bodys->setMeta('title', "Noticias");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "noticias");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap">
            <h1 class="tcenter">Noticias</h1>
        </div>
    </div>
    <div class="fullwrap spacing">
        <div class="wrap">
            <div class="margin-wrap">
                <?php
                $blog = new Notas($db);
                if(isset($_GET["p"]) && !empty($_GET["p"])){
                    $page = $_GET["p"];
                }else{
                    $page = 1;
                }
                $limite = 6;
                $_GET['dm_currenturl'] = URL.$controlador->name.'?go=1';
                $total = $blog->getTotal();
                $totalNotas = count($blog->notas);
                if($total > 0){
                    $blog->get($page,$limite);
                    $blog->printNotas();
                }
                $blog->paginacion($total, $totalNotas, $limite, $page);
                ?>
            </div>
        </div>
    </div>
	<div class="fullwrap spacing-small-top">
        <div class="wrap">
            <h1 class="tcenter">Nuestras publicaciones</h1>
        </div>
    </div>
	<div class="fullwrap spacing spacing-small-top">
        <div class="wrap">
            <div class="margin-wrap">
                <?php
                $publicaciones = new Publicaciones($db);
                if(isset($_GET["pp"]) && !empty($_GET["pp"])){
                    $page = $_GET["pp"];
                }else{
                    $page = 1;
                }
                $limite = 6;
                $_GET['dm_currenturl'] = URL.$controlador->name.'?gop=1';
                $total = $publicaciones->getTotal();
                $totalNotas = count($publicaciones->notas);
                if($total > 0){
                    $publicaciones->get($page,$limite);
                    $publicaciones->printNotas();
                }
                $publicaciones->paginacion($total, $totalNotas, $limite, $page);
                ?>
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
