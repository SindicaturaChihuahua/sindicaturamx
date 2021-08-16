<?php
include_once(DMCLASSES.'auditorias.php');
include_once(DMCLASSES.'auditoria.php');
$bodys->set('titulo', "Revisiones");
$bodys->setMeta('title', "Revisiones");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "revisiones");
// Head
require_once( VIEWS .'head.php');
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapbig">
            <div class="auditorias-top">
                <h1 class="tcenter">Revisiones</h1>
                <p>En función de las facultades de inspección y vigilancia que tiene esta  Sindicatura  de acuerdo con los artículos 30, 36 A y 36 B, fracción III del Código Municipal Para el Estado de Chihuahua; en la siguiente sección presentamos los avances y resultados de las revisiones y auditorías que llevamos a cabo a las diferentes áreas del gobierno municipal.</p>
            </div>
            <img class="decor decor08" src="<?=URLIMAGES;?>decor08.png" alt="">
			<div class="iconografia">
				<h3 class="highlight-blue">Esta iconografía indica la etapa en la que se encuentra la revisión:</h3>
				<?php
				$iconografia = getIconosEtapas();
				foreach ($iconografia as $nombre => $info ){
                    if($info["descripcion"]){
                        echo '<div class="iconografia-box">
    						<div class="margin-wrap">
    							<div class="ib-left">
    								<div class="ib-icono etapa-icono etapa-icono-'.$info["color"].'">'.$info["icono"].'</div>
    								'.$nombre.'
    							</div>
    							<div class="ib-right">
    								<p>'.$info["descripcion"].'</p>
    							</div>
    						</div>
    					</div>';
                    }

				}
				?>
			</div>
        </div>
    </div>
    <div class="fullwrap spacing no-top">
        <div class="wrap wrapbig">
            <div class="comision-miembros">
                <?php
                $auditorias = new Auditorias($db);
                $auditorias->printAuditoriasByCategoria();
                ?>
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
