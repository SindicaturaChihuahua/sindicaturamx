<?php
class Plan {
	protected $db;
	public $databaseprefix;
	public $data = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_plan";
	}

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'plan/obj'.$this->data['post_id'].'/';
		$imagenes = array("descarga");
		foreach ($imagenes as $imagen) {
			if(isset($this->data[$imagen]) && validaGeneral($this->data[$imagen], 4)){
				$this->data['has_'.$imagen] = true;
				$this->data[$imagen] = $this->data['folderpath'].$this->data[$imagen];
			}else{
				$this->data['has_'.$imagen] = false;
			}
		}
	}
	public function getPlan($id = 1){
		$sql = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE post_id = ? LIMIT 1";
		$parametros = array($id);

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
	public function printPlan(){
        if($this->data["has_descarga"]){
            echo '<p><a class="btn-secondary" href="'.$this->data["descarga"].'" target="_blank">Descárgalo Aquí</a></p>';
        }

	}
}
?>
