<?php
class back_configuracion extends Seccion {

	function __construct($db)
	{
		$this->db = $db;
        $this->name = "configuracion";
        $this->order = '1';
        $this->version = '1.0';
		$this->tab = 'editor';
		$this->displayName = 'Configuración';
		$this->description = 'Descripcion';
		$this->icon = 'cog';
		$this->databaseprefix = 'opciones';
		$this->permiso = 'Administracion';
		$this->static_urls = array(
			'lista' => 'p/'.$this->name,
			'nuevo-handler' => 'p/'.$this->name.'/nuevo-handler'
		);
		$this->social_opciones = array(
			'facebook'=>array('Facebook', 'facebook'),
			'twitter'=>array('Twitter', 'twitter'),
			'instagram'=>array('Instagram', 'instagram'),
			'youtube'=>array('Youtube', 'youtube'),
			'linkedin'=>array('LinkedIn', 'linkedin'),
			'youtube'=>array('Youtube', 'youtube')
		);
	}

	public function call($accion,$gama=false,$delta=false) {
		if($accion=="lista"){
			return array(
				"file" => "lista",
			);
		}else if($accion=="nuevo-handler"){
			return array(
				"file" => "nuevo-handler",
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

	public function getLinks(){
		$objetos=array();
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE status != ?");
		$stmt->execute(array('revision'));
		if($objetostmp = $stmt->FetchAll()){
			foreach($objetostmp as $objeto){
				$objetos[$objeto['nav_id']]=$objeto;
			}
		}
		return $objetos;
	}

	public function getObjeto($id){
		if(is_numeric($id)){
			$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE nav_id = ? LIMIT 1");
			$stmt->execute(array($id));
			if($row = $stmt->Fetch()){
				return $row;
			}
		}
		return false;
	}

	public function newObjeto($autor=0){
		$stmt = $this->db->prepare("SELECT nav_id FROM ".DB_PREFIX.$this->databaseprefix." WHERE autor_id = ? AND status = ? LIMIT 1");
		$stmt->execute(array($autor,'revision'));
		$stmt->bindColumn('nav_id', $id);
		if($row = $stmt->Fetch()){
			return $id;
		}else{
			$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX.$this->databaseprefix." (autor_id, status) VALUES (?, ?)");
			$stmt->execute(array($autor,'revision'));
			$id = $this->db->lastInsertId();
			return $id;
		}
	}

	public function generateSigaMenu($campos, $level=0){
		$arbol=array();
		if(isset($campos['obj'])){
			foreach($campos['obj'] as $id => $padre){
				if(isset($padre) && is_numeric($padre)){
					if(!isset($arbol['s'.$padre])){
						$arbol['s'.$padre]=array($padre,$id);
					}else{
						$arbol['s'.$padre][]=$id;
					}
					if(!isset($arbol['s'.$id])){
						$arbol['s'.$id]=array($id);
					}
				}else{
					if(!isset($arbol['s'.$id])){
						$arbol['s'.$id]=array($id);
					}
				}
			}
		}
		return $arbol;
	}

	public function printSigaMenu(&$menu, &$objetos, $edit_url, $level=0){
		foreach($menu as $s => $elementos){
			$this->printMenuElement($objetos[$elementos[0]]['nav_id'],$objetos[$elementos[0]]['titulo']);
			$objetos[$elementos[0]]['activoenmenu']='si';
			if(count($elementos)>1){
				echo '<ol>';
					$this->printSigaMenuInterno($menu,$objetos,$edit_url,1,$elementos);
				echo '</ol>';
			}
			echo '</li>';
			unset($menu->{'s'.$elementos[0]});
		}
	}

	public function printSigaMenuInterno(&$menu, &$objetos, $edit_url, $level=0, $elementos){
		for($a=1;$a<count($elementos);$a++){
			$this->printMenuElement($objetos[$elementos[$a]]['nav_id'],$objetos[$elementos[$a]]['titulo']);
			$objetos[$elementos[$a]]['activoenmenu']='si';
			if(isset($menu->{'s'.$elementos[$a]})){
				echo '<ol>';
					$this->printSigaMenuInterno($menu,$objetos,$edit_url,1,$menu->{'s'.$elementos[$a]});
				echo '</ol>';
			}
			echo '</li>';
			unset($menu->{'s'.$elementos[$a]});
		}
	}

	public function printMenuElement($id,$titulo){
		echo '<li id="obj_'.$id.'"><div class="nselement" id="obj_'.$id.'"><i class="fa fa-th-large handle"></i><a href="'.$id.'">'.cleanOutput($titulo).'</a></div>';
	}

	public function printSigaMenuInactive($objetos, $edit_url){
		foreach($objetos as $obj){
			if(!isset($obj['activoenmenu'])){
				$this->printMenuElement($obj['nav_id'],$obj['titulo']);
				echo '</li>';
			}
		}
	}

	public function saveOpcionesJSON(){
		$opciones = getOpciones();
		$otras_opciones = getOtrasOpciones();
		$final=array(
			'opciones' => $opciones,
			'otras_opciones' => $otras_opciones
		);
		$folder = ROOT.'private/';
		crearDirectorio($folder);
		if(file_exists($folder.'dm_opciones.json')){
			if($fh = fopen($folder.'dm_opciones.json', "w")){
				if(fwrite($fh, json_encode($final))){
					fclose($fh);
					return true;
				}
			}
		}
		cerrarDirectorio($folder);
		return false;
	}

	public function templateRedes($obj=false){
		if($obj){
			echo '<div class="anelm">';
			echo '<div class="handle"><div class="hicon"><i class="fa fa-sort" aria-hidden="true"></i></div></div>';
			echo '<input type="hidden" name="red_orden[]" value="">';
			echo '<div class="aeacts"><a href="#" class="btn btn-danger btn-xs deleteaction">Eliminar</a></div>';
			echo '<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label class="control-label">Red</label>
						<select class="form-control" name="red_red[]">';
							foreach ($this->social_opciones as $key => $value) {
								if($key==$obj[0]){
									echo '<option value="'.$key.'" selected="selected">'.$value[0].'</option>';
								}else{
									echo '<option value="'.$key.'">'.$value[0].'</option>';
								}
							}
						echo '</select>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="form-group">
						<label class="control-label">Enlace</label>
						<input type="text" class="form-control" name="red_enlace[]" value="'.$obj[1].'" />
					</div>
				</div>
			</div>';
			echo '</div>';
		}
	}

}
?>
