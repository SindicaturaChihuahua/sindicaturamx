<?php
class Pages
{
	protected $db;
	public $pages=array();
 
    public function __construct($db) {
		$this->db = $db;
    }
 
    // Get Editable Pages
    public function getEditablePages() {	
 		$params = array(1);
		$result = $this->db->rawQuery("SELECT *
			FROM ".DB_PREFIX."tmp_pages 
			WHERE editable = ? 
			ORDER BY pid DESC"
			, $params);
		if(!empty($result)){
			foreach($result as $row) {
				$this->pages[$row["pid"]] = $row;
			}
			return true;
		}
		return false;
    }
	
    // Get Project by friendly url
    public function getPage($pid) {
 		if(isset($pid) && is_numeric($pid)){
			if(!isset($this->pages[$pid])){
				$params = array($pid);
				$result = $this->db->rawQuery("SELECT * FROM ".DB_PREFIX."tmp_pages WHERE pid = ? LIMIT 1", $params);
				
				if (!empty($result)) {
					$this->pages[$pid] = $result[0];
					return $this->pages[$pid];
				}else{
					$this->pages[$pid]=false;
				}
			}else{
				return $this->pages[$pid];	
			}
		}
		return false;
    }
	
    // Get Project by friendly url
    public function getPageExtraInfo($pid) {
 		if(isset($pid) && is_numeric($pid)){
			$params = array($pid);
			$result = $this->db->rawQuery("SELECT * FROM ".DB_PREFIX."tmp_pages_extras WHERE pid = ?", $params);
			
			if (!empty($result)) {
				$data=array();
				foreach($result as $r){
					$data[$r['dataname']]=$r;
				}
				return $data;
			}
		}
		return false;
    }

}
?>