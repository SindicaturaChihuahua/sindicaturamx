<?php
class Comisiones {
	protected $db;
	public $databaseprefix;
	public $comisiones = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_acomisiones";
	}

	public function get(){
		$query = "SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
			FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
			WHERE t1.status = ? AND visibilidad = ?
			ORDER BY t1.orden ASC, t1.modificado DESC";
		$params = array('publicado', 'publico');
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			$comisiones=$stmt->FetchAll();
			foreach($comisiones as $key => $tmpcomision){
				$comision = new Comision($this->db);
				$comision->initFromData($tmpcomision);
				$this->comisiones[] = $comision;
			}
		}
	}
	function getCategoria($slug){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_categorias WHERE status = 'publicado' AND categoria_slug = ? LIMIT 1");
		$stmt->execute(array($slug));
		$dtmp = $stmt->fetch();
		if($dtmp){
			$categoria = $dtmp;
			if($this->noEmptyCategoria($categoria['categoria_id'])){
				$categoria["no_empty"] = true;
				$categoria['folderpath'] = URLCARGAS.'blog/categorias/obj'.$categoria['categoria_id'].'/';
				$imagenes = array("categoria_cover", "categoria_img1");
				foreach ($imagenes as $imagen) {
					if(isset($categoria[$imagen]) && validaGeneral($categoria[$imagen], 4)){
						$categoria['has_'.$imagen] = true;
						$categoria[$imagen.'_image'] = $categoria['folderpath'].$categoria[$imagen];
						$categoria[$imagen.'_image_medium'] = $categoria['folderpath']."medium_".$categoria[$imagen];
						$categoria[$imagen.'_image_big'] = $categoria['folderpath']."big_".$categoria[$imagen];
					}else{
						$categoria['has_'.$imagen] = false;
						$categoria[$imagen.'_image'] = URLIMAGES.'default/blog_'.$imagen.'.png';
						$categoria[$imagen.'_image_medium'] = URLIMAGES.'default/blog_'.$imagen.'.png';
						$categoria[$imagen.'_image_big'] = URLIMAGES.'default/blog_'.$imagen.'.png';
					}
				}
				return $categoria;
			}else{
				$categoria["no_empty"] = false;
			}
		}
	}
	public function printComisiones(){
		for($i=0; $i < count($this->comisiones); $i++){
			$this->comisiones[$i]->printComision();
		}
	}
}
?>
