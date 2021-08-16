<?php
class seccionesSlider {
	protected $db;
	public $databaseprefix;
	public $slides = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_slidesecciones";
	}

	public function get($orden="orden",$limit=1000){
		if($stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."
			WHERE status = 'publicado' AND visibilidad = 'publico'
			ORDER BY ".$orden." ASC, modificado DESC LIMIT ".$limit)){
			$stmt->execute();
			$slides=$stmt->FetchAll();
			foreach($slides as $key => $slide){
				$seccionslide = new seccionesSlide($this->db);
				$seccionslide->initFromData($slide);
				$this->slides[] = $seccionslide;
			}
		}
	}
	public function printSlider(){
		echo '<div class="owl-carousel owl-theme owlHomeEnlaces">';
		for($i=0; $i < count($this->slides); $i++){
			$this->slides[$i]->printSlide();
		}
		echo '</div>';
	}
}
?>
