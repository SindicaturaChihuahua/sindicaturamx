<?php
header("Location: ".URL.'acciones-y-programas');
include_once(DMCLASSES.'convocatorias.php');
include_once(DMCLASSES.'convocatoria.php');
include_once(DMCLASSES.'rubros.php');
include_once(DMCLASSES.'rubro.php');
include_once(DMCLASSES.'plan.php');
$bodys->set('titulo', "Contraloría Social");
$bodys->setMeta('title', "Contraloría Social");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "contraloria");
// Head
require_once( VIEWS .'head.php');
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapmed">
			<div class="margin-wrap">
				<div class="contraloria-top">
					<h1>Contraloría Social</h1>
					<p>La Contraloría Social, es el mecanismo para que la ciudadanía, especialmente aquellas personas beneficiarias, verifiquen el cumplimiento de las metas y la correcta aplicación de los recursos públicos asignados a programas de desarrollo social de manera organizada.</p>
				</div>
				<div class="contraloria-top">
					<img src="<?=URLIMAGES;?>contraloria.png" alt="Contraloría Social">
				</div>
			</div>
        </div>
		<div class="wrap wrapmed">
			<div class="contraloria-plan">
				<h1>Plan Municipal de Desarrollo 2018 - 2021</h1>
				<div class="margin-wrap">
					<div class="contraloria-top ctp">
						<p>El Programa Municipal de Desarrollo es el plan que tu gobierno municipal realiza a tres años, para lograr el bien común así como la prosperidad de las y los habitantes, lograr el bien común, mediante la solidaridad y la subsidiariedad (que todas las acciones tiendan hacia el empoderamiento y desarrollo de las personas).</p>
					</div>
					<div class="contraloria-top ctp">
						<p>Documento cuantitativo y cualitativo que contiene las políticas públicas, los objetivos, las estrategias y las líneas de acción, que conforme a las capacidades institucionales y presupuestales se habrán de desarrollar durante la administración.</p>
						<?php
						$plan = new Plan($db);
						$plan->getPlan();
						$plan->printPlan();
						?>
					</div>
				</div>
			</div>
		</div>
    </div>
	<div class="fullwrap spacing">
		<div class="wrap wrapmed">
			<h1>Cumplimiento del Plan Municipal de Desarrollo</h1>
			<p class="rubros-p">Aquí encontrarás los avances del Plan Municipal de Desarrollo en cada uno de sus siete ejes. Al descargar los archivos podrás revisar el progreso de cada programa e indicador, para que puedas conocer a detalle los resultados del trabajo en nuestro municipio.</p>

			<p class="rubros-p">Sindicatura es invitado permanente del Comité de Planeación para el Desarrollo del Municipio (COPLADEMUN), instancia donde se conjunta el esfuerzo y trabajo de las autoridades municipales y la sociedad para la definición, priorización y evaluación de los programas gubernamentales. Como parte de este organismo, la Sindicatura presenta los avances al momento:</p>

			<?php
			$rubros = new Rubros($db);
			$rubros->get(1,999);
			$rubros->printRubros();
			?>
		</div>
	</div>
	<div class="fullwrap spacing">
		<div class="wrap wrapmed">
			<h1>Conoce los apoyos y programas sociales del Municipio</h1>
			<div class="margin-wrap">
				<div class="contraloria-top bttm">
					<p class="contraloria-bottom-p">Todos los programas sociales de nuestro municipio deben de tener reglas claras y transparentes para lograr que los apoyos y estrategias tengan un impacto real sobre las personas. Es por esto, que aquí ponemos a su disposición las convocatorias y programas sociales que se llevarán a cabo este año.</p>
				</div>
				<div class="contraloria-top bttm">
					<img src="<?=URLIMAGES;?>contraloria2.png" alt="Contraloria Social">
				</div>
			</div>
		</div>
	</div>
	<div class="fullwrap spacing no-top">
        <div class="wrap wrapmed">
            <div class="comision-miembros">
                <?php
                $convocatorias = new Convocatorias($db);
				$convocatorias->get(1,999);
                $convocatorias->printConvocatorias();
                ?>
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
