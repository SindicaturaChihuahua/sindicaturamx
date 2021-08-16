<?php
class ComisionMiembro {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $tagline;
	public $titulo_truncado;
	public $slug;
	public $tipo;
	public $descripcion;
	public $extracto;
	public $comisiones;
	public $extra;
	public $categoria;
	public $fecha;
	public $enlaces;
	public $tresdetres = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_amiembros";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'amiembros/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover","thumb","archivo","icono","patrimonial","intereses","fiscal");
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
		$this->extracto = truncar_cadena(strip_tags($this->descripcion), 220, ".", '.');
		$this->fecha = formatDate($this->data["modificado"],"formal");
		$this->enlaces = json_decode($this->data['enlaces'], true);
		$this->tresdetres[0] = $this->data["patrimonial_image"];
		$this->tresdetres[1] = $this->data["intereses_image"];
		$this->tresdetres[2] = $this->data["fiscal_image"];
		// $this->url = URL.$this->categoria["categoria_slug"].'/'.$this->slug;
		$this->url = URL.'nosotros/'.$this->slug;
		$this->comisiones = $this->getComisiones($this->post_id);

		if(isset($this->data['extra'])){
			$this->extra = json_decode($this->data['extra'], true);
		}
	}
	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}
	public function printMiembro(){
		$partidos_politicos = getPartidos();
		echo '<div class="nosotros-equipo-box open-miembro" data-open="modal-'.$this->slug.'">
			<div class="nosotros-equipo-img" style="background-image:url('.$this->data["cover_image_big"].');">
				<div class="nosotros-equipo-img-hover">
					<div class="mas-info">
						<i class="fas fa-search-plus"></i>
						<span>Más Información</span>
					</div>
				</div>
				<img class="nosotros-equipo-icono" src="'.$partidos_politicos[$this->tagline]["icono"].'" alt="'.$partidos_politicos[$this->tagline]["nombre"].'">
			</div>
			<h3>'.$this->titulo.'</h3>
			<div class="nosotros-equipo-tagline">'.$this->data["comision_puesto"].'</div>
		</div>';
	}
	public function printAllMiembro(){
		$comision = $this->getComisionPresidente($this->post_id);
		$partidos_politicos = getPartidos();
		echo '<div class="nosotros-equipo-box open-miembro" data-open="modal-'.$this->slug.'">
			<div class="nosotros-equipo-img" style="background-image:url('.$this->data["cover_image_big"].');">
				<div class="nosotros-equipo-img-hover">
					<div class="mas-info">
						<i class="fas fa-search-plus"></i>
						<span>Más Información</span>
					</div>
				</div>
				<img class="nosotros-equipo-icono" src="'.$partidos_politicos[$this->tagline]["icono"].'" alt="'.$partidos_politicos[$this->tagline]["nombre"].'">
			</div>
			<h3>'.$this->titulo.'</h3>
			<div class="nosotros-equipo-tagline">'.$comision["titulo"].'</div>
		</div>';
	}
	public function printDestacado(){
		echo '<div class="nosotros-equipo-box open-miembro" data-open="modal-'.$this->slug.'">
			<div class="nosotros-equipo-img nosotros-equipo-img-destacado" style="background-image:url('.$this->data["cover_image_big"].');">
				<div class="nosotros-equipo-img-hover">
					<div class="mas-info">
						<i class="fas fa-search-plus"></i>
						<span>Más Información</span>
					</div>
				</div>
			</div>
			<h3>'.$this->titulo.'</h3>
			<div class="nosotros-equipo-tagline">'.$this->tagline.'</div>
		</div>';
	}
	public function printModalMiembro($comision){
		$partidos_politicos = getPartidos();
		$trestxt = get3de3();
		$tresdetres = '';
		for ($i=0; $i < count($this->enlaces); $i++) {
			if(!empty($this->enlaces[$i]["url"])){
				$trestxt[$i]["url"] = $this->enlaces[$i]["url"];
				$tresdetres .= '<a class="tdt-'.$i.'" href="'.$trestxt[$i]["url"].'" target="_blank" title="'.$trestxt[$i]["nombre"].'">'.$trestxt[$i]["icono"].'</a>';
			}
		}
		$tresdetreswrap = '';
		if(!empty($tresdetres)){
			$tresdetreswrap = '<div class="modal-tresdetres">
				<img class="tresdetres-logo" src="'.URLIMAGES.'miembro-tresdetres.png">
				'.$tresdetres.'
			</div>';
		}
		$archivo = '';
		if($this->data["has_archivo"]){
			$archivo = '<a href="'.$this->data["archivo_image"].'" class="modal-cv" target="_blank"><img src="'.URLIMAGES.'pdf-icon.png" alt="Descargar CV"><div class="btn-secondary">Descargar CV</div></a>';
		}
		echo '<div class="modal-miembro modal-'.$this->slug.'">
			<div class="wrap">
				<div class="modal-cerrar">Cerrar<span></span></div>
				<div class="modal-wrap">
					<div class="modal-img">
						<div class="astable">
							<div class="ascell">
								<div class="modal-cover">
									<div class="modal-cover-img" style="background-image:url('.$this->data["cover_image_big"].')">
										<img class="modal-cover-icono" src="'.$partidos_politicos[$this->tagline]["icono"].'" alt="'.$partidos_politicos[$this->tagline]["nombre"].'">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-txt">
						<div class="modal-txt-wrap">
							<h2>'.$this->titulo.'</h2>
							<span class="modal-tagline">'.$this->data["comision_puesto"].' de la Comisión de '.$comision.'</span>
							<div class="modal-contenido">
								'.$this->descripcion.'
							</div>
							<div class="modal-enlaces">
								'.$tresdetreswrap.$archivo.'
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	public function printModalAllMiembro(){
		$partidos_politicos = getPartidos();
		$trestxt = get3de3();
		$tresdetres = '';
		for ($i=0; $i < count($this->enlaces); $i++) {
			if(!empty($this->enlaces[$i]["url"])){
				$trestxt[$i]["url"] = $this->enlaces[$i]["url"];
				$tresdetres .= '<a class="tdt-'.$i.'" href="'.$trestxt[$i]["url"].'" target="_blank" title="'.$trestxt[$i]["nombre"].'">'.$trestxt[$i]["icono"].'</a>';
			}
		}
		$tresdetreswrap = '';
		if(!empty($tresdetres)){
			$tresdetreswrap = '<div class="modal-tresdetres">
				<img class="tresdetres-logo" src="'.URLIMAGES.'miembro-tresdetres.png">
				'.$tresdetres.'
			</div>';
		}
		$archivo = '';
		if($this->data["has_archivo"]){
			$archivo = '<a href="'.$this->data["archivo_image"].'" class="modal-cv" target="_blank"><img src="'.URLIMAGES.'pdf-icon.png" alt="Descargar CV"><div class="btn-secondary">Descargar CV</div></a>';
		}
		$comisiones = '';
		if(!empty($this->comisiones)){
			$comisionesnombres = $this->getComisionesNombres();
			for($i=0; $i < count($this->comisiones); $i++){

				$comisiones .= '-'.$this->comisiones[$i]["comision_puesto"].' de la Comisión de '.$comisionesnombres[$this->comisiones[$i]["comision_id"]]["titulo"].'<br>';
			}
		}
		echo '<div class="modal-miembro modal-'.$this->slug.'">
			<div class="wrap">
				<div class="modal-cerrar">Cerrar<span></span></div>
				<div class="modal-wrap">
					<div class="modal-img">
						<div class="astable">
							<div class="ascell">
								<div class="modal-cover">
									<div class="modal-cover-img" style="background-image:url('.$this->data["cover_image_big"].')">
										<img class="modal-cover-icono" src="'.$partidos_politicos[$this->tagline]["icono"].'" alt="'.$partidos_politicos[$this->tagline]["nombre"].'">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-txt">
						<div class="modal-txt-wrap">
							<h2>'.$this->titulo.'</h2>
							<div class="modal-contenido">
								'.$this->descripcion.'
								<p class="highlight-blue">'.$comisiones.'</p>
							</div>
							<div class="modal-enlaces">
								'.$tresdetreswrap.$archivo.'
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	public function printModalDestacado(){
		$trestxt = get3de3();
		$tresdetres = '';
		for ($i=0; $i < count($this->enlaces); $i++) {
			if(!empty($this->enlaces[$i]["url"])){
				$trestxt[$i]["url"] = $this->enlaces[$i]["url"];
				$tresdetres .= '<a class="tdt-'.$i.'" href="'.$trestxt[$i]["url"].'" target="_blank" title="'.$trestxt[$i]["nombre"].'">'.$trestxt[$i]["icono"].'</a>';
			}
		}
		$tresdetreswrap = '';
		if(!empty($tresdetres)){
			$tresdetreswrap = '<div class="modal-tresdetres">
				<img class="tresdetres-logo" src="'.URLIMAGES.'miembro-tresdetres.png">
				'.$tresdetres.'
			</div>';
		}
		$archivo = '';
		if($this->data["has_archivo"]){
			$archivo = '<a href="'.$this->data["archivo_image"].'" class="modal-cv" target="_blank"><img src="'.URLIMAGES.'pdf-icon.png" alt="Descargar CV"><div class="btn-secondary">Descargar CV</div></a>';
		}
		echo '<div class="modal-miembro modal-'.$this->slug.'">
			<div class="wrap">
				<div class="modal-cerrar">Cerrar<span></span></div>
				<div class="modal-wrap">
					<div class="modal-img">
						<div class="astable">
							<div class="ascell">
								<div class="modal-cover">
									<div class="modal-cover-img modal-cover-img-destacado" style="background-image:url('.$this->data["cover_image_big"].')">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-txt">
						<div class="modal-txt-wrap">
							<h2>'.$this->titulo.'</h2>
							<span class="modal-tagline">'.$this->tagline.'</span>
							<div class="modal-contenido">
								'.$this->descripcion.'
							</div>
							<div class="modal-enlaces">
								'.$tresdetreswrap.$archivo.'
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
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
	public function getComisiones($id){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_comisiones WHERE miembro_id = '$id' AND status = 'publico' ORDER BY orden ASC, comision_id ASC");
		$stmt->execute();
		return $stmt->FetchAll();
	}
	public function getComisionesNombres(){
		$comisionesnombres = array();
		for($i=0; $i < count($this->comisiones); $i++){
			$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."s_acomisiones WHERE post_id = ? AND status = ? AND visibilidad = ? LIMIT 1");
			$params = array($this->comisiones[$i]["comision_asignada"], 'publicado', 'publico');
			$stmt->execute($params);
			$comisionesnombres[$this->comisiones[$i]["comision_id"]]=$stmt->fetch();;
		}
		return $comisionesnombres;
	}
	public function getComisionPresidente($id){
		$query = "SELECT t1.*
		   FROM ".DB_PREFIX."s_acomisiones AS t1
		   INNER JOIN ".DB_PREFIX.$this->databaseprefix."_comisiones AS t2
		   ON t1.post_id = t2.comision_asignada
		   WHERE t2.miembro_id = ? AND t2.comision_puesto = ? AND t1.status = ? AND t1.visibilidad = ?
		   LIMIT 1";
		$params = array($id, "Presidente(a)", 'publicado', 'publico');
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			return $comision=$stmt->fetch();
		}
	}
	public function parseVideo($video){
		$parsed = parse_url($video);
		if($parsed["host"] == "youtu.be"){
			return $parsed["path"];
		}elseif($parsed["host"] == "www.youtube.com" || $parsed["host"] == "youtube.com"){
			$query = strstr($parsed["query"], '&', true);
			if($query == false){
				$query = str_replace("v=","",$parsed["query"]);
			}else{
				$query = str_replace("v=","",$query);
			}
			return $query;
		}
	}
}
?>
