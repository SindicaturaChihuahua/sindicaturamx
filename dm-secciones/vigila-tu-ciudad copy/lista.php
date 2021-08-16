<?php
include_once(DMCLASSES.'obras.php');
include_once(DMCLASSES.'obra.php');
$bodys->set('titulo', "Vigila tu Ciudad");
$bodys->setMeta('title', "Vigila tu Ciudad");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("bottomjs", '//maps.googleapis.com/maps/api/js?key=AIzaSyCVjHZTXG-NSlm3IRI5sgWCwQ-KkFbqvfo');
$bodys->set("bottomjs", URL.'dm-secciones/vigila-tu-ciudad/js/egeoxml.js');
$bodys->set("page", "nuestraciudad");
// Head
require_once( VIEWS .'head.php');
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapmed">
			<div class="margin-wrap">
				<div class="contraloria-top">
					<h1 class="tcenter">Vigila tu Ciudad</h1>
                    <p>Una de las tareas más importante de todos los municipios es desarrollar obras y prestar servicios públicos de calidad.</p>
                    <p>En la Sindicatura tenemos la tarea de vigilar que esto se cumpla, pero, además, estamos convencidos que la mejor manera de hacerlo es involucrando a todas las personas que quieran participar de este trabajo.</p>
                    <p>Por eso, en este mapa podrás ubicar y conocer los aspectos más importantes de las obras públicas, el mantenimiento de parques, la recolección de basura o el desarrollo urbano de la ciudad de Chihuahua.</p>
                    <p>Si tienes alguna duda o identificas que no se está cumpliendo adecuadamente con alguna de estas tareas haz tu denuncia o súmate al programa Guardianes Ciudadanos.</p>
				</div>
				<div class="contraloria-top">
					<img src="<?=URLIMAGES;?>nuestra-ciudad.png" alt="Nuestra Ciudad">
				</div>
			</div>
        </div>
		<div class="wrap wrapmed">
            <div class="margin-wrap obras-wrap image-first">
                <div class="obras-sidebar">
                    <?php
                    $obras = new Obras($db);
                    $obras->printByYears();
                    ?>
                </div>
                <div class="obras-mapa">
                    <div id="mapa" class="obras-mapa-wrap"></div>
                </div>
            </div>
		</div>
        <div class="wrap wrapmed bg-obras-bottom">
            <div class="obras-bottom">
                <h3 class="highlight-blue">¿Crees que alguna de las tareas o asuntos del mapa está mal o puede mejorarse?<br>
                Tienes dos opciones:</h3>
                <div class="obras-opcion1">
                    <p>Hacer una denuncia anónima <a href="https://denuncia.sindicatura.mx/" target="_blank">aquí</a>.</p>
                    <a href="https://www.sindicatura.mx/informacion/denuncia" target="_blank"><img class="imgfluid" src="<?=URLIMAGES;?>vigila-img1.png" alt="Haz tu denuncia"></a>
                </div>
                <div class="obras-opcion2">
                    <h3 class="highlight-blue">Súmate al programa de Guardianes Ciudadanos</h3>
                    <p>En este programa podrás colaborar con la Sindicatura aprendiendo y haciendo Contraloría Social.</p>
                    <p>Te llevaremos de la mano y te capacitaremos para que conozcas los contratos de servicios públicos u obras públicas. De esta manera podrás ayudar a la ciudad a identificar:</p>
                    <ul>
                        <li>Problemas en la ejecución de obras o servicios.</li>
                        <li>Fallas administrativas.</li>
                        <li>Posibles actos de corrupción. </li>
                    </ul>
                    <div class="obras-bottom-enlaces">
                        <a class="highlight-blue" href="https://www.sindicatura.mx/informacion/guardianesciudadanos" target="_blank">Conoce más del programa</a>
                        <a class="highlight-blue" href="https://docs.google.com/forms/d/e/1FAIpQLSeS1n4Q9TlekbViLxVrg4LhAYSWNjAj6zi_Nq8VXQfTIemWSQ/viewform?usp=send_form" target="_blank">Inscríbete aquí</a>
                    </div>
                    <img class="vigila-img2" src="<?=URLIMAGES;?>vigila-img2.png" alt="Guardianes Ciudadanos">
                </div>
            </div>
            <div class="obras-bottom-footer">
                En tus manos está el poder de construir una mejor ciudad
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
