<?php
class Pages {
	protected $db;
	public $databaseprefix;
	public $pages = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_landing";
	}

	public function get(){
		$query = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE status = ? AND visibilidad = ? ORDER BY modificado DESC";
		$params = array('publicado', 'publico');
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			$pages=$stmt->FetchAll();
			foreach($pages as $key => $pagetmp){
				$page = new Vacante($this->db);
				$page->initFromData($pagetmp);
				$this->pages[] = $page;
			}
		}
	}
	public function printPages(){
		if(count($this->pages) > 0){
			for($i=0; $i < count($this->pages); $i++){
				$this->pages[$i]->printPage();
			}
		}
	}
}
?>
