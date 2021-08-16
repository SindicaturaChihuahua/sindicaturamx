<?php
class navegacion extends Seccion {

	function __construct($db)
	{
		$this->db = $db;
        $this->name = "navegacion";
        $this->order = '1';
        $this->version = '1.0';
		$this->tab = 'editor';
		$this->displayName = 'Navegación';
		$this->description = 'Descripcion';
		$this->icon = 'link';
		$this->databaseprefix = 'navegacion';
		$this->permiso = 'EditorJefe';
		$this->static_urls = array(
			'lista' => 'p/'.$this->name,
			'nuevo' => 'p/'.$this->name.'/nuevo',
			'editar' => 'p/'.$this->name.'/editar',
			'nuevo-handler' => 'p/'.$this->name.'/nuevo-handler',
			'orden-handler' => 'p/'.$this->name.'/orden-handler'
		);
		$this->enlace_targets = array(
			'_self'=>'Abrir enlace en la misma ventana',
			'_blank'=>'Abrir enlace en una nueva ventana'
		);
	}

	public function call($accion,$gama=false,$delta=false) {
		if($accion=="lista"){
			return array(
				"file" => "lista",
			);
		}else if($accion=="nuevo" || $accion=="editar"){
			return array(
				"file" => "edit"
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
			$this->printMenuElement($objetos[$elementos[0]]['nav_id'],$objetos[$elementos[0]]['titulo'],$edit_url);
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
			$this->printMenuElement($objetos[$elementos[$a]]['nav_id'],$objetos[$elementos[$a]]['titulo'],$edit_url);
			$objetos[$elementos[$a]]['activoenmenu']='si';
			if(isset($menu->{'s'.$elementos[$a]}) && count($menu->{'s'.$elementos[$a]})>1){
				echo '<ol>';
					$this->printSigaMenuInterno($menu,$objetos,$edit_url,1,$menu->{'s'.$elementos[$a]});
				echo '</ol>';
			}
			echo '</li>';
			unset($menu->{'s'.$elementos[$a]});
		}
	}

	public function printMenuElement($id,$titulo,$edit_url){
		echo '<li id="obj_'.$id.'"><div class="nselement" id="obj_'.$id.'"><i class="fa fa-th-large handle"></i><a href="'.$edit_url.$id.'">'.cleanOutput($titulo).'</a></div>';
	}

	public function printSigaMenuInactive($objetos, $edit_url){
		foreach($objetos as $obj){
			if(!isset($obj['activoenmenu'])){
				$this->printMenuElement($obj['nav_id'],$obj['titulo'],$edit_url);
				echo '</li>';
			}
		}
	}

	public function printMenu(&$menu, &$objetos, $level=0){
		foreach($menu as $s => $elementos){
			$this->printMenuElementNormal($objetos[$elementos[0]]);
			$objetos[$elementos[0]]['activoenmenu']='si';
			if(count($elementos)>1){
				echo '<span>&nbsp;<i class="fa fa-angle-down"></i></span>';
				echo '<ol>';
					$this->printMenuInterno($menu,$objetos,1,$elementos);
				echo '</ol>';
			}
			echo '</li>';
			unset($menu->{'s'.$elementos[0]});
		}
	}

	public function printMenuInterno(&$menu, &$objetos, $level=0, $elementos){
		for($a=1;$a<count($elementos);$a++){
			$this->printMenuElementNormal($objetos[$elementos[$a]]);
			$objetos[$elementos[$a]]['activoenmenu']='si';
			if(isset($menu->{'s'.$elementos[$a]}) && count($menu->{'s'.$elementos[$a]})>1){
				echo '<ol>';
					$this->printMenuInterno($menu,$objetos,1,$menu->{'s'.$elementos[$a]});
				echo '</ol>';
			}
			echo '</li>';
			unset($menu->{'s'.$elementos[$a]});
		}
	}

	public function printMenuElementNormal($obj){
		if($obj['linka']!="#"){
			if(validateURL($obj['linka'])){
				$url=$obj['linka'];
			}else{
				$url=URL.$obj['linka'];
			}
			$url='<a href="'.$url.'" target="'.$obj['target'].'">'.cleanOutput($obj['titulo']).'</a>';
		}else{
			$url='<span>'.cleanOutput($obj['titulo']).'</span>';
		}
		echo '<li id="obj_'.$obj['nav_id'].'">'.$url;
	}

}
?>
