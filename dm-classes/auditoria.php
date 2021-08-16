<?php
class Auditoria {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $post_id;
	public $titulo;
	public $etapa;
	public $slug;
	public $tipo;
	public $extra;
	public $categoria;
	public $fecha;
	public $iconoEtapa;
	public $archivos = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_auditorias";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'auditorias/obj'.$this->data['post_id'].'/';
		$imagenes = array("cover","acta","archivo");
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
		$this->categoria = $this->getCategoria($this->post_id);
		$this->fecha = formatDate($this->data["modificado"],"front");
		$this->etapa = $this->data["etapa"];
		$iconosEtapas = getIconosEtapas();
		$this->iconoEtapa = '<div class="etapa-icono etapa-icono-'.$iconosEtapas[$this->etapa]["color"].'">'.$iconosEtapas[$this->etapa]["icono"].'</div>';
		// $this->url = URL.$this->categoria["categoria_slug"].'/'.$this->slug;
		$this->url = URL.'auditorias/'.$this->slug;
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

	public function getAuditoria($slug){
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
	public function getById($post_id){
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
	public function printAuditoria(){
		$dictamenes = '';
		if(!empty($this->archivos)){
			for ($i=0; $i < count($this->archivos) ; $i++) {
				$dictamenes .= '<a class="pdf-icon" href="'.$this->data['folderpath'].$this->archivos[$i]["nombre"].'" target="_blank" title="'.$this->archivos[$i]["descripcion"].'"><img src="'.URLIMAGES.'pdf-icon.png" alt="'.$this->archivos[$i]["descripcion"].'"></a>';
			}
		}
		$etapas = getEtapas();
		echo '<tr>
			<td data-label="Nombre: ">'.$this->titulo.'</td>
			<td data-label="Fecha: ">'.$this->fecha.'</td>
			<td data-label="Etapa: " class="td-etapas">'.$this->iconoEtapa.' '.$this->etapa.'</td>
			<td data-label="Descargas: ">'.$dictamenes.'</td>
		</tr>';
	}
	public function getCategoria($post_id){
		$stmt = $this->db->prepare("SELECT t1.* FROM ".DB_PREFIX.$this->databaseprefix."_categorias AS t1 INNER JOIN ".DB_PREFIX.$this->databaseprefix."_relation AS t2 ON t1.categoria_id = t2.categoria_id WHERE t2.post_id = ? LIMIT 1");
		$stmt->execute(array($post_id));
		$dtmp = $stmt->fetch();
		if($dtmp){
			return $dtmp;
		}
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
