<?php
class Indicador {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $tagline;
	public $slug;
	public $tipo;
	public $descripcion;
	public $textos;
	public $graficas;
	public $extra;
	public $fecha;
	public $fecha2;
	public $fechaCompleta;
	public $archivos = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_comovamos";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'comovamos/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover","thumb","archivo");
		foreach ($imagenes as $imagen) {
			if(isset($this->data[$imagen]) && validaGeneral($this->data[$imagen], 4)){
				$this->data['has_'.$imagen] = true;
				$this->data[$imagen.'_image'] = $this->data['folderpath'].$this->data[$imagen];
				$this->data[$imagen.'_image_medium'] = $this->data['folderpath']."medium_".$this->data[$imagen];
				$this->data[$imagen.'_image_big'] = $this->data['folderpath']."big_".$this->data[$imagen];
			}else{
				$this->data['has_'.$imagen] = false;
				$this->data[$imagen.'_image'] = URLIMAGES.'default/blog_'.$imagen.'.png';
				$this->data[$imagen.'_image_medium'] = URLIMAGES.'default/blog_'.$imagen.'.png';
				$this->data[$imagen.'_image_big'] = URLIMAGES.'default/blog_'.$imagen.'.png';
			}
		}
		$this->titulo = $this->data["titulo"];
		$this->titulo_truncado = truncar_cadena($this->data["titulo"], 76, " ");
		$this->tagline = $this->data["tagline"];
		$this->slug = $this->data["slug"];
		$this->tipo = $this->data["tipo"];
		$this->descripcion = $this->data["descripcion"];
		$this->textos = json_decode($this->data['textos'], true);
		$this->graficas = json_decode($this->data['graficas'], true);

		if(formatDate($this->data["modificado"],"year") == formatDate($this->data["modificado2"],"year")){
			$this->fecha = formatDate($this->data["modificado"],"indicador_noyear");
			$this->fecha2 = formatDate($this->data["modificado2"],"indicador");

		}else{
			$this->fecha = formatDate($this->data["modificado"],"indicador");
			$this->fecha2 = formatDate($this->data["modificado2"],"indicador");
		}
		$this->fechaCompleta = 'Del '.$this->fecha.' al '.$this->fecha2;
		$this->url = URL.'como-vamos/'.$this->slug;
		$this->archivos = $this->getArchivos($this->post_id);
		if(isset($this->data['extra'])){
			$this->extra = json_decode($this->data['extra'], true);
		}
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function getIndicador($slug){
		$sql = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE slug = ? LIMIT 1";
		$parametros = array($slug);

        if($stmt = $this->db->prepare($sql)){
			$stmt->execute($parametros);
			$dtmp = $stmt->fetch();
			if($dtmp){
				$this->data = $dtmp;
				$this->post_id = $this->data["post_id"];
				$this->init();
				return true;
			}
		}
		return false;
    }
	public function getIndicadorById($post_id){
		$sql = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE post_id = ? LIMIT 1";
		$parametros = array($post_id);

        if($stmt = $this->db->prepare($sql)){
			$stmt->execute($parametros);
			$dtmp = $stmt->fetch();
			if($dtmp){
				$this->data = $dtmp;
				$this->post_id = $post_id;
				$this->init();
				return true;
			}
		}
		return false;
    }
	public function printCover(){
		if($this->data["has_thumb"]){
			echo '<div class="indicador-card card-cover">
				<div class="indicador-card-container">
					<h3>'.$this->tagline.'</h3>
					<div class="indicador-card-content">
						<img src="'.$this->data["thumb_image_big"].'" alt="'.$this->tagline.'">
					</div>
				</div>
			</div>';
		}
	}
	public function printIndicador(){
		echo '<a href="'.$this->url.'" class="indicador-box">
		    <div class="indicador-inner">
		        <img src="'.$this->data["cover_image_big"].'" alt="'.$this->titulo.'">
		        <h2>'.$this->titulo.'</h2>
		        <div class="indicador-inner-txt">
		            <h3>'.$this->textos[0]["titulo"].'</h3>
		            <span class="indicador-inner-monto">'.$this->textos[0]["texto"].'</span>
		        </div>
		        <div class="indicador-inner-txt">
		            <h3>'.$this->textos[1]["titulo"].'</h3>
		            <span class="indicador-inner-monto">'.$this->textos[1]["texto"].'</span>
		        </div>
		        <div class="indicador-inner-fecha">
		            '.$this->fechaCompleta.'
		        </div>
		    </div>
		    <div class="indicador-txt">
				'.$this->descripcion.'
				<p class="tcenter"><span class="btn-secondary">Conoce más</span></p>
		    </div>
		</a>';
	}
	public function printGraficas(){
		for ($i=0; $i < count($this->graficas); $i++) {
			if(!empty($this->graficas[$i]["titulo"])){
				$this->graficas[$i]["valores"] = $this->getValores($this->post_id,$i);
				if(!empty($this->graficas[$i]["valores"])){
					// $doble = '';
					// if($this->graficas[$i]["tipo"] == "horizontalBar" || $this->graficas[$i]["tipo"] == "bar"){
					// 	$doble = ' card-two-columns';
					// }
					echo '<div class="indicador-card card-two-columns">
						<div class="indicador-card-container">
							<h3>'.$this->graficas[$i]["titulo"].'</h3>
							<div class="indicador-card-content">
							<p class="indicador-card-descripcion">'.$this->graficas[$i]["descripcion"].'</p>';
							echo '<div class="indicador-grafica"><canvas id="grafica-'.$i.'"></canvas></div>';
							if($this->graficas[$i]["mostrardatos"]){
								$numero = 0;
								echo '<div class="indicador-labels">';
								foreach ($this->graficas[$i]["valores"] as $valor) {
									if(strlen($valor["valor_val"]) > 5){
										$break = '<br>';
									}else{
										$break = ' ';
									}
									echo '<p><span class="indicador-graficas-colorbox colorbox-'.$numero.'"></span><strong>'.$valor["valor_nombre"].':</strong>'.$break.$valor["valor_texto"].'</p>';
									$numero++;
								}
								echo '</div>';
							}
					echo '</div>
						</div>
					</div>';
				}
			}
		}
	}
	public function printGraficasJS(){
		for ($i=0; $i < count($this->graficas); $i++) {
			if(!empty($this->graficas[$i]["titulo"])){
				$this->graficas[$i]["valores"] = $this->getValores($this->post_id,$i);
				if(!empty($this->graficas[$i]["valores"])){
					$nombres = '';
					$textos = '';
					$datas = '';
					foreach ($this->graficas[$i]["valores"] as $valor) {
						$nombres .= "'".$valor["valor_nombre"]."', ";
						$textos .= "'".$valor["valor_texto"]."', ";
						$datas .= "'".$valor["valor_val"]."', ";
					}
					$fill = '';
					if($this->graficas[$i]["tipo"] == "line"){
						$fill = 'fill: false,';
					}
					$charts[] = "var ctx".$i." = $('#grafica-".$i."');
			        var myChart".$i." = new Chart(ctx".$i.", {
			            type: '".$this->graficas[$i]["tipo"]."',
			            data: {
			                labels: [".$nombres."],
							textos: [".$textos."],
			                datasets: [{
			                    label:false,
			                    data: [".$datas."],
			                    backgroundColor: [
			                        '#224f8f',
			                        '#e38b77',
			                        '#eccb4e',
									'#abd0e4',
									'#f37554',
									'#53bd60',
									'#c1036c',
									'#aa6b9e',
									'#5a6a32',
									'#3ecdfe',
									'#cc0000',
									'#cccccc',
									'#982282',
									'#0424a7',
									'#18944b',
									'#1d88b6',
									'#6d6468',
									'#f56438',
									'#d9e396',
									'#a3545a',
									'#9b7b0b'
			                    ],
								".$fill."
			                }]
			            },
			            options: {
			                scales: {
			                    yAxes: [{
									display:false,
			                        ticks: {
			                            beginAtZero:true,
										callback: function(value, index, values) {
					                        return number_format(value);
					                    }
			                        }
			                    }],
								xAxes: [{
									display:false,
								}]
			                },
							tooltips: {
								callbacks: {
					                label: function(tooltipItem, data) {
										var label = data.textos[tooltipItem.index];
					                    return label;
					                }
								}
					        },
							legend: { display: false },
							title: {
								display: false,
								text: '".$this->graficas[$i]["titulo"]."'
							}
			            }
			        });";
				}
			}
		}
		echo '<script type="text/javascript">
		$(document).ready(function(e){ ';
		foreach ($charts as $chart){
			echo $chart;
		}
		// for ($i=0; $i < count($chart); $i++) {
		// 	echo $chart[$i];
		// }
		echo '});
		</script>';
	}

	public function getValores($id,$grafica){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_valores WHERE indicador_id = ".$id." AND grafica_asignada = ".$grafica." AND status = 'publico' ORDER BY orden ASC, modificado DESC");
		$stmt->execute();
		return $stmt->FetchAll();
	}
	public function getArchivos($id){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_archivos WHERE objeto_id = ? AND status = ? ORDER BY orden ASC");
		$stmt->execute(array($id,'publish'));
		return $stmt->FetchAll();
	}
	public function printArchivos(){
		if(!empty($this->archivos)){
			echo '<div class="indicador-card">
				<div class="indicador-card-container">
					<h3>Descargas</h3>
					<div class="indicador-card-content">';
					for ($i=0; $i < count($this->archivos) ; $i++) {
						echo '<p><a class="btn-primary btn-primary-icon" href="'.$this->data['folderpath'].$this->archivos[$i]["nombre"].'" target="_blank" title="'.$this->archivos[$i]["descripcion"].'"><img src="'.URLIMAGES.'file-icon.png" alt="'.$this->archivos[$i]["descripcion"].'"> '.$this->archivos[$i]["descripcion"].'</a></p>';
					}
			echo '</div>
				</div>
			</div>';

		}
	}

	public function isOpen(){
		if($this->data['status']=="publicado" && $this->data['visibilidad']!="oculto"){
			return true;
		}
		return false;
	}
	public function addView(){
		$this->db->query("UPDATE ".DB_PREFIX.$this->databaseprefix." SET visitas=visitas+1 WHERE post_id = ".$this->post_id);
	}
}




// scales: {
// 	yAxes: [{
// 		ticks: {
// 			beginAtZero:true,
// 			callback: function(value, index, values) {
// 				return '$ ' + number_format(value);
// 			}
// 		}
// 	}]
// },
// tooltips: {
// 	callbacks: {
// 		label: function(tooltipItem, chart){
// 			var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
// 			return datasetLabel + ': $ ' + number_format(tooltipItem.yLabel, 2);
// 		}
// 	}
// },
?>
