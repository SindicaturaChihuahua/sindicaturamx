<?php
class Seccion
{
	protected $db;

    public function __construct($db) {
		$this->db = $db;
    }

	public function getURL(){
		return URL . $this->name;
	}

	public function includePath(){
		return DMSECCIONES . $this->name . "/";
	}

	public function assetsPath(){
		return URL . 'dm-secciones/' . $this->name . "/";
	}

	public function printAdministracionInfo(){
		echo '<h2>'.$this->displayName.'</h2>';
		echo '<h3>'.$this->description.'</h3>';
	}

	public function install(){
		if($stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX."secciones (nombre, displaynombre, description, version, tab, orden) VALUES ('".$this->name."', '".$this->displayName."', '".$this->description."', '".$this->version."', '".$this->tab."', ".$this->order.")")){
			$stmt->execute();
			$stmt->close();
			return true;
		}
		return false;
	}

	public function update(){
		if($stmt = $this->db->prepare("UPDATE ".DB_PREFIX."secciones SET nombre = '".$this->name."', displaynombre = '".$this->displayName."', description = '".$this->description."', version = '".$this->version."', tab = '".$this->tab."', orden = ".$this->order." WHERE nombre = '".$this->name."'")){
			$stmt->execute();
			$stmt->close();
			return true;
		}
		return false;
	}

	public function uninstall(){
		return true;
	}
}
?>
