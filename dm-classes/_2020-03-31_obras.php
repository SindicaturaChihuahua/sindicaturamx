<?php
class Obras {
	protected $db;
	public $databaseprefix;
	public $obras = array();
	public $years = array();
	public $byYear = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_obras";
		$this->years = getYears();
		$this->years = array_reverse($this->years);
	}

	public function get($pagina=1, $limit=999, $tipo=false){
		$limite1 = ($pagina-1)*$limit;
		$addtipo = "";
		if($tipo){
			$addtipo = " AND t1.tipo = '".$tipo."'";
		}
		$query = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE status = ? AND visibilidad = ?".$addtipo."
			ORDER BY orden ASC, modificado DESC LIMIT ".$limite1.",".$limit;
		$params = array('publicado', 'publico');
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			$obras=$stmt->FetchAll();
			foreach($obras as $key => $obratmp){
				$obra = new Obra($this->db);
				$obra->initFromData($obratmp);
				$this->obras[] = $obra;
			}
		}
	}
	public function getByYear($categoria){
		$query = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE categoria = ? AND status = ? AND visibilidad = ? ORDER BY orden ASC, modificado DESC";
		$params = array($categoria,'publicado', 'publico');
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			$obras=$stmt->FetchAll();
			foreach($obras as $key => $obratmp){
				$obra = new Obra($this->db);
				$obra->initFromData($obratmp);
				$this->obras[] = $obra;
			}
		}
	}
	public function printByYears(){
		foreach($this->years as $y) {
			$year[$y] = new Obras($this->db);
			$year[$y]->getByYear($y);
			if(!empty($year[$y]->obras)){
				$this->byYear[$y] = $year[$y]->obras;
			}
		}
		if(!empty($this->byYear)){
			foreach ($this->byYear as $y => $obras) {
				echo '<div class="obras a-'.$y.'">
					<div class="obras-heading abrir-year" data-open="'.$y.'"><img src="'.URLIMAGES.'icono-carpeta.svg" alt="Ver año '.$y.'" data-open="'.$y.'">'.$y.'<span></span></div>
					<div class="obras-contenido c-'.$y.'">';
					for ($i=0; $i < count($obras); $i++) {
						$obras[$i]->printObra();
					}
				echo '</div>
				</div>';
			}
		}
	}
	public function getTotal($tipo=false){
		$addtipo = "";
		$addtipo2 = "";
		if($tipo){
			$addtipo = " AND t1.tipo = '".$tipo."'";
			$addtipo2 = " AND tipo = '".$tipo."'";
		}
		$query = "SELECT COUNT(post_id) FROM ".DB_PREFIX.$this->databaseprefix." WHERE status = ? AND visibilidad = ?".$addtipo2;
		$params = array('publicado','publico');
		$stmt = $this->db->prepare($query);
		$stmt->execute($params);
		$total_obj = $stmt->fetch(PDO::FETCH_COLUMN);
		return $total_obj;
	}
	// public function formatByYear($year,$contenido){
	// 	echo '<div class="obras a-'.$year.'">
	// 		<div class="obras-heading"><img src="'.URLIMAGES.'icono-carpeta.svg" alt="Ver año '.$year.'">'.$year.'</div>
	// 		<div class="obras-contenido c-'.$year.'">
	// 			<button class="">'.NOMBRE.'</button>
	// 		</div>
	// 	</div>';
	// }


	public function groupByCategoria($array,$key){
		$return = array();
	    foreach($array as $val) {
	        $return[$val->$key][] = $val;
	    }
		// for ($i=0; $i < count($array); $i++) {
		// 	$return[$array[$i][$key]][] = $array[$i];
		// }
	    return $return;
	}



}
?>
