<?php
class Evaluaciones {
	protected $db;
	public $databaseprefix;
	public $data = array();
	public $link;
	public $link2;
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_evaluaciones";
	}
	public function get($post_id = 1){
		$sql = "SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE post_id = ? LIMIT 1";
		$parametros = array($post_id);

        if($stmt = $this->db->prepare($sql)){
			$stmt->execute($parametros);
			$dtmp = $stmt->fetch();
			if($dtmp){
				$this->data = $dtmp;
				$this->post_id = $post_id;
				$this->init();
				return true;
			}
		}
		return false;
    }

	public function init(){
		$this->data['folderpath'] = URLCARGAS.'evaluaciones/obj'.$this->data['post_id'].'/';
		$imagenes = array("cimtra","imco");
		foreach ($imagenes as $imagen) {
			if(isset($this->data[$imagen]) && validaGeneral($this->data[$imagen], 4)){
				$this->data['has_'.$imagen] = true;
				$this->data[$imagen.'_image'] = $this->data['folderpath'].$this->data[$imagen];
				$this->data[$imagen.'_image_medium'] = $this->data['folderpath']."medium_".$this->data[$imagen];
				$this->data[$imagen.'_image_big'] = $this->data['folderpath']."big_".$this->data[$imagen];
			}else{
				$this->data['has_'.$imagen] = false;
				$this->data[$imagen.'_image'] = URLIMAGES.'default/slide'.$imagen.'.png';
				$this->data[$imagen.'_image_medium'] = URLIMAGES.'default/slide'.$imagen.'.png';
				$this->data[$imagen.'_image_big'] = URLIMAGES.'default/slide'.$imagen.'.png';
			}
		}
		if(filter_var($this->data["btn_link"], FILTER_VALIDATE_URL)){
			$this->link = $this->data['btn_link'];
		}
		if(filter_var($this->data["btn_link2"], FILTER_VALIDATE_URL)){
			$this->link2 = $this->data['btn_link2'];
		}
	}

	public function initFromData($data){
		$this->data = $data;
		$this->post_id = $data['post_id'];
		$this->init();
	}

	public function printEvaluaciones(){
		if($this->data["has_cimtra"]){
			$link = '';
			$img = '<img class="evaluacion-cover" src="'.$this->data["cimtra_image_big"].'" alt="CIMTRA">';
			if(!empty($this->link)){
				$link = '<a class="btn-primary" href="'.$this->link.'" target="_blank">Mostrar más detalles</a>';
				$img = '<a href="'.$this->link.'" target="_blank">'.$img.'</a>';
			}
			echo '<div class="evaluacion">';
				echo '<div class="evaluacion-wrap">';
					echo $img;
					echo '<div class="evaluacion-txt">
						<div class="evaluacion-txt-box">
							<img src="'.URLIMAGES.'logo-cimtra.png" alt="CIMTRA">
							<h2>CIMTRA</h2>
						</div>
						<div class="evaluacion-txt-box">
							'.$link.'
						</div>
					</div>';
				echo '</div>';
			echo '</div>';
		}
		if($this->data["has_imco"]){
			$link2 = '';
			$img2 = '<img class="evaluacion-cover" src="'.$this->data["imco_image_big"].'" alt="IMCO">';
			if(!empty($this->link2)){
				$link2 = '<a class="btn-primary" href="'.$this->link2.'" target="_blank">Mostrar más detalles</a>';
				$img2 = '<a href="'.$this->link2.'" target="_blank">'.$img2.'</a>';
			}
			echo '<div class="evaluacion">';
				echo '<div class="evaluacion-wrap">';
					echo $img2;
					echo '<div class="evaluacion-txt">
						<div class="evaluacion-txt-box">
							<img src="'.URLIMAGES.'logo-imco.png" alt="IMCO">
							<h2>IMCO</h2>
						</div>
						<div class="evaluacion-txt-box">
							'.$link2.'
						</div>
					</div>';
				echo '</div>';
			echo '</div>';
		}
	}
}
?>
