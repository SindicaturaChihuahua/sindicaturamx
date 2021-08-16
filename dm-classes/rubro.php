<?php
class Rubro {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $slug;
	public $tipo;
	public $extra;
	public $fecha;
	public $archivos = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_porcentajes";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'porcentajes/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover");
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
		$this->slug = $this->data["slug"];
		$this->tipo = $this->data["tipo"];
		$this->fecha = formatDate($this->data["modificado"],"front");
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
	public function printRubro(){
		$archivos = '';
		if(!empty($this->archivos)){
			for ($i=0; $i < count($this->archivos) ; $i++) {
				$archivos .= '<a class="pdf-icon" href="'.$this->data['folderpath'].$this->archivos[$i]["nombre"].'" target="_blank" title="'.$this->archivos[$i]["descripcion"].'"><img src="'.URLIMAGES.'pdf-icon.png" alt="'.$this->archivos[$i]["descripcion"].'"></a>';
			}
		}
		echo '<div class="contraloria-rubro">
			<div class="margin-wrap">
				<div class="rubro-left">
					<h2>'.$this->titulo.'</h2>
					<img src="'.$this->data["cover_image_big"].'" alt="'.$this->titulo.'">
				</div>
				<div class="rubro-right">';
				// if(!empty($archivos)){
				// 	echo '<h2>Descargas</h2>
				// 	'.$archivos;
				// }
				echo $archivos;

			echo '</div>
			</div>
		</div>';
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
}
?>
