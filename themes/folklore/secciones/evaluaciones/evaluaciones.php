<?php
class back_evaluaciones extends Seccion {

	function __construct($db)
	{
		$this->db = $db;
        $this->name = "evaluaciones";
        $this->order = '1';
        $this->version = '1.0';
		$this->tab = 'indicadores';
		$this->displayName = 'Evaluaciones externas';
		$this->description = 'Evaluaciones externas';
		$this->icon = 'image';
		$this->databaseprefix = 's_evaluaciones';
		$this->permiso = 'Editor';
		$this->static_urls = array(
			'lista' => 's/'.$this->name,
			'nuevo' => 's/'.$this->name.'/nuevo',
			'editar' => 's/'.$this->name.'/editar',
			'nuevo-handler' => 's/'.$this->name.'/nuevo-handler',
			'orden-handler' => 's/'.$this->name.'/orden-handler',
			'single-upload-handler' => 's/'.$this->name.'/single-upload-handler',
			'categorias' => 's/'.$this->name.'/categorias',
			'categorias-nuevo' => 's/'.$this->name.'/categorias/nuevo',
			'categorias-editar' => 's/'.$this->name.'/categorias/editar',
			'categorias-nuevo-handler' => 's/'.$this->name.'/categorias/categorias-nuevo-handler',
			'categorias-single-upload-handler' => 's/'.$this->name.'/categorias/categorias-single-upload-handler',
		);
		$this->single_files=array(
			'cimtra' => array(
				'required' => array("pdf","doc","docx","xls","xlsx","jpg","jpge","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;CIMTRA',
				'info' => 'Gráfico CIMTRA ',
				'tipoarchivo' => 'image'
			),
			'imco' => array(
				'required' => array("pdf","doc","docx","xls","xlsx","jpg","jpge","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;IMCO',
				'info' => 'Gráfico IMCO ',
				'tipoarchivo' => 'image'
			)
		);
		$this->formatos=array(
			'imagen' => "Imagen",
			'video' => "Video"
		);
	}

	public function call($accion,$gama=false,$delta=false) {
		if($accion=="lista"){
			return array(
				"file" => "edit",
			);
		}else if($accion=="nuevo" || $accion=="editar"){
			return array(
				"file" => "edit",
				"css" => array(
					URLPLUGINS.'selectize/selectize.default.css',
					'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css'
				)
			);
		}else if($accion=="single-upload-handler"){
			return array(
				"file" => "single-upload-handler",
				"ajax" => true
			);
		}else if($accion=="nuevo-handler"){
			return array(
				"file" => "nuevo-handler",
				"ajax" => true
			);
		}else if($accion=="orden-handler"){
			return array(
				"file" => "orden-handler",
				"ajax" => true
			);
		}
    }

	function install()
	{
		if (parent::install()){
			return true;
		}
		return false;
	}

	function update(){
		parent::update();
	}

	function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

	public function getPost($id){
		if(is_numeric($id)){
			$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE post_id = ? LIMIT 1");
			$stmt->execute(array($id));
			if($row = $stmt->Fetch()){
				return $row;
			}
		}
		return false;
	}

	public function newPost($autor=0){
		$stmt = $this->db->prepare("SELECT post_id FROM ".DB_PREFIX.$this->databaseprefix." WHERE autor_id = ? AND status = ? LIMIT 1");
		$stmt->execute(array($autor,'revision'));
		$stmt->bindColumn('post_id', $id);
		if($row = $stmt->Fetch()){
			return $id;
		}else{
			$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX.$this->databaseprefix." (autor_id, status) VALUES (?, ?)");
			$stmt->execute(array($autor,'revision'));
			$post_id = $this->db->lastInsertId();
			return $post_id;
		}
	}

	public function existsUniqueSlug($slug,$id){
		if($stmt = $this->db->prepare("SELECT post_id FROM ".DB_PREFIX.$this->databaseprefix." WHERE slug = ? LIMIT 1")){
			$stmt->execute(array($slug));
			$stmt->bindColumn('post_id',$oid);
			if($stmt->Fetch()){
				if($oid!=$id){
					return true;
				}
			}
		}
		return false;
	}

	public function generateUniqueSlug($slug){
		$slugF=$slug."-".rand(99,9999);
		return $slugF;
	}

}
?>
