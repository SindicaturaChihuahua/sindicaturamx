<?php
class Obra {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $categoria;
	public $color;
	public $slug;
	public $tipo;
	public $extra;
	public $fecha;
	public $seccion;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_obras";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'obras/obj'.$this->data['post_id'].'/';
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
		$files = array("archivo");
		foreach ($files as $file) {
			if(isset($this->data[$file]) && validaGeneral($this->data[$file], 4)){
				$this->data['has_'.$file] = true;
				$this->data[$file.'_file'] = $this->data['folderpath'].$this->data[$file];
			}else{
				$this->data['has_'.$file] = false;
				$this->data[$file.'_file'] = false;
			}
		}
		$this->titulo = $this->data["titulo"];
		$this->titulo_truncado = truncar_cadena($this->data["titulo"], 76, " ");
		$this->color = $this->data["color"];
		$this->slug = $this->data["slug"];
		$this->categoria = $this->data["categoria"];
		$this->seccion = $this->data["seccion"];
		$this->fecha = formatDate($this->data["modificado"],"formal");
		// $this->url = URL.'nuestra-ciudad/'.$this->slug;
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function getObra($slug){
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
	public function getObraById($post_id){
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
	// public function printObra(){
	// 	if($this->data["has_archivo"]){
	// 		echo '<div class="obra ow-'.$this->post_id.'">
	// 			<div class="obra-titulo abrir-obra" data-open="'.$this->post_id.'" data-archivo="'.$this->data["archivo"].'" style="background-color:'.$this->color.'">'.$this->titulo.'</div>
	// 			<div id="o-'.$this->post_id.'" class="obra-lista"></div>
	// 		</div>';
	// 	}
	// }
	public function printObra(){
		if($this->data["has_archivo"]){
			echo '<div class="obra ow-'.$this->post_id.'">
				<div class="obra-titulo abrir-obra" data-open="'.$this->post_id.'" data-archivo="'.$this->data["archivo"].'" style="background-color:'.$this->color.'">'.$this->titulo.'</div>
				<div>
				<div class="obra-search">
					<input class="obras-search-input s-'.$this->post_id.'" data-id="'.$this->post_id.'" type="text">
					<button id="btns-'.$this->post_id.'" class="obras-search-btn" data-id="'.$this->post_id.'" type="button">Buscar</button>
					<button id="btns-'.$this->post_id.'" class="obras-clear-btn" data-id="'.$this->post_id.'" type="button">X</button>
				</div>
				</div>
				<div id="o-'.$this->post_id.'" class="obra-lista ol-'.$this->post_id.'"></div>
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

?>
