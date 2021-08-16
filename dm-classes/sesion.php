<?php
class Sesion {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $lugar;
	public $tagline;
	public $slug;
	public $tipo;
	public $video;
	public $extra;
	public $fecha;
	public $archivos = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_asesiones";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'asesiones/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover","archivo","acta");
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
		$this->tagline = $this->data["tagline"];
		$this->slug = $this->data["slug"];
		$this->tipo = $this->data["tipo"];
		$this->video = $this->data["video"];
		$this->fecha = formatDate($this->data["modificado"],"front");
		$this->url = URL.'ayuntamiento/sesiones-de-cabildo/'.$this->slug;
		if(isset($this->data['extra'])){
			$this->extra = json_decode($this->data['extra'], true);
		}
		$this->archivos = $this->getArchivos($this->post_id);
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function getSesion($slug){
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
	public function getSesionById($post_id){
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
	public function printSesion(){
		$dictamenes = '';
		if(!empty($this->archivos)){
			for ($i=0; $i < count($this->archivos) ; $i++) {
				$dictamenes .= '<a class="pdf-icon" href="'.$this->data['folderpath'].$this->archivos[$i]["nombre"].'" target="_blank" title="'.$this->archivos[$i]["descripcion"].'"><img src="'.URLIMAGES.'pdf-icon.png" alt="'.$this->archivos[$i]["descripcion"].'"></a>';
			}
		}
		$video = '';
		if(!empty($this->video)){
			$video = '<a href="'.$this->video.'" target="_blank"><i class="fas fa-play-circle"></i> Ver transmisión</a>';
		}
		echo '<tr>
			<td data-label="Sesión: ">'.$this->titulo.'</td>
			<td data-label="Fecha: ">'.$this->fecha.'</td>
			<td data-label="Video: ">'.$video.'</td>
			<td data-label="Documentos: ">'.$dictamenes.'</td>
		</tr>';
	}
	public function getArchivos($id){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_archivos WHERE objeto_id = ? AND status = ? ORDER BY orden ASC");
		$stmt->execute(array($id,'publish'));
		return $stmt->FetchAll();
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



	public function printSesiones(){
		if(!empty($this->sesiones)){
			echo '<div class="reset-table comision-sesiones">
			    <h3>Sesiones</h3>
			    <table class="comision-sesiones-tabla">
			        <thead>
			            <tr>
			                <th>Sesión</>
			                <th>Video</th>
			                <th>Dictámenes - Descarga</th>
			            </tr>
			        </thead>
			        <tbody>';
					foreach ($this->sesiones as $sesion) {
						$dictamenes = '';
						$archivos = $this->getArchivos($sesion["sesion_id"]);
						if(!empty($archivos)){
							for ($i=0; $i < count($archivos) ; $i++) {
								$dictamenes .= '<a class="pdf-icon" href="'.$this->data['folderpathsesiones'].$archivos[$i]["nombre"].'" target="_blank" title="'.$archivos[$i]["descripcion"].'"><img src="'.URLIMAGES.'pdf-icon.png" alt="'.$archivos[$i]["descripcion"].'"></a>';
							}
						}
						$video = '';
						if(!empty($sesion["sesion_video"])){
							$video = '<a href="'.$sesion["sesion_video"].'" target="_blank">'.$sesion["sesion_video"].'</a>';
						}
						echo '<tr>
							<td>'.$sesion["sesion_nombre"].'</td>
							<td>'.$video.'</td>
							<td>'.$dictamenes.'</td>
						</tr>';
					}
					echo '
			        </tbody>
			    </table>
			</div>';
		}
	}
}
?>
