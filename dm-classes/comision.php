<?php
class Comision {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $tagline;
	public $slug;
	public $tipo;
	public $descripcion;
	public $extra;
	public $sesiones = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_acomisiones";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'acomisiones/obj'.$this->data['post_id'].'/';
		$this->data['folderpathsesiones'] = URLCARGAS.'acomisiones/sesiones/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover","archivo");
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
		$this->descripcion = $this->data["descripcion"];
		$this->extracto = truncar_cadena(strip_tags($this->descripcion), 300, ".", '.');
		// $this->url = URL.$this->categoria["categoria_slug"].'/'.$this->slug;
		$this->url = URL.'ayuntamiento/comisiones/'.$this->slug;
		if(isset($this->data['extra'])){
			$this->extra = json_decode($this->data['extra'], true);
		}
		$this->sesiones = $this->getSesiones($this->post_id);
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function getComision($slug){
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
	public function getComisionById($post_id){
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
	public function printComision(){
		echo '<div class="comisiones-box">
			<a class="comision" href="'.$this->url.'">
				<div class="comision-img">
					<img src="'.$this->data["cover_image_big"].'" alt="'.$this->titulo.'">
				</div>
				<h3>'.$this->titulo.'</h3>
			</a>
		</div>';
	}
	public function getSesiones($id){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_sesiones WHERE comision_id = ".$id." AND status = 'publicado' ORDER BY modificado DESC");
		$stmt->execute();
		return $stmt->FetchAll();
	}
	public function printSesiones(){
		if(!empty($this->sesiones)){
			echo '<div class="reset-table comision-sesiones">
			    <h3>Sesiones</h3>
			    <table class="comision-sesiones-tabla">
			        <thead>
			            <tr>
			                <th>Sesión</th>
			                <th>Acta</th>
			                <th>Video</th>
			                <th>Documentos</th>
			            </tr>
			        </thead>
			        <tbody>';
					foreach ($this->sesiones as $sesion) {
						$sesfolder = URLCARGAS.'acomisiones/sesiones/obj'.$sesion["sesion_id"].'/';
						$dictamenes = '';
						$archivos = $this->getArchivos($sesion["sesion_id"]);
						if(!empty($archivos)){

							for ($i=0; $i < count($archivos) ; $i++) {
								$dictamenes .= '<a class="pdf-icon" href="'.$sesfolder.$archivos[$i]["nombre"].'" target="_blank" title="'.$archivos[$i]["descripcion"].'"><img src="'.URLIMAGES.'pdf-icon.png" alt="'.$archivos[$i]["descripcion"].'"></a>';
							}
						}
						$video = '';
						if(!empty($sesion["sesion_video"])){
							$video = '<a href="'.$sesion["sesion_video"].'" target="_blank"><i class="fas fa-play-circle"></i> Ver transmisión</a>';
						}
						$acta = '';
						if(!empty($sesion["sesion_acta"])){
							$acta = '<a class="pdf-icon" href="'.$sesfolder.$sesion["sesion_acta"].'" target="_blank" title="'.$sesion["sesion_nombre"].'"><img src="'.URLIMAGES.'pdf-icon.png" alt="'.$sesion["sesion_nombre"].'"></a>';
						}
						echo '<tr>
							<td data-label="Sesión: ">'.$sesion["sesion_nombre"].'</td>
							<td data-label="Acta: ">'.$acta.'</td>
							<td data-label="Video: ">'.$video.'</td>
							<td data-label="Documentos: ">'.$dictamenes.'</td>
						</tr>';
					}
					echo '
			        </tbody>
			    </table>
			</div>';
		}
	}
	public function getArchivos($id){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_sesiones_archivos WHERE objeto_id = ? AND status = ? ORDER BY orden ASC");
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

	public function printNewsletter(){
		echo '<div class="comision-newsletter">
			<form class="object-ajax" action="'.URL.'api/registros">
				<input type="hidden" name="action" value="sendemail">
				<input type="hidden" name="paginareferencia" value="'.$this->titulo.'">
				<input type="hidden" name="id" value="'.$this->post_id.'">
				<h2 class="highlight-blue">¿Deseas recibir información sobre esta comisión?</h2>
				<input type="text" name="correo" placeholder="Escribe tu correo electrónico">
				<button type="submit" class="btn-primary">Enviar</button>
			</form>
		</div>';
	}
}
?>
