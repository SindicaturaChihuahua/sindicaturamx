<?php
class Consejo {
	protected $db;
	public $databaseprefix;
	public $miembros = array();
	function __construct($db)
	{
		$this->db = $db;
		$this->databaseprefix = "s_consejo";
	}

	public function get($pagina=1, $limit=999, $tipo=false){
		$limite1 = ($pagina-1)*$limit;
		$addtipo = "";
		if($tipo){
			$addtipo = " AND t1.tipo = '".$tipo."'";
		}
		$query = "SELECT t1.*, t2.username, t2.pseudonimo, t2.cover as usercover
            FROM ".DB_PREFIX.$this->databaseprefix." AS t1 INNER JOIN ".DB_PREFIX."users AS t2 ON t1.autor_id = t2.uid
            WHERE t1.status = ? AND visibilidad = ?".$addtipo."
            ORDER BY t1.modificado DESC LIMIT ".$limite1.",".$limit;
    	$params = array('publicado', 'publico');
		if($stmt = $this->db->prepare($query)){
			$stmt->execute($params);
			$miembros=$stmt->FetchAll();
			foreach($miembros as $key => $tmpmiembro){
				$miembro = new ConsejoMiembro($this->db);
				$miembro->initFromData($tmpmiembro);
				$this->miembros[] = $miembro;
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
	public function printModalMiembros(){
		for($i=0; $i < count($this->miembros); $i++){
			$this->miembros[$i]->printModalMiembro();
		}
	}
	public function printMiembros(){
		if(count($this->miembros) > 0){
			echo '<div class="nosotros-equipo consejo-equipo">';
			echo '<div class="margin-wrap">';
			for($i=0; $i < count($this->miembros); $i++){
				$this->miembros[$i]->printMiembro();
			}
			echo '</div>';
			echo '</div>';
		}
	}
	public function printMiembrosByCategoria(){
		if(!$this->categorias){
			$this->getCategorias();
		}
		foreach ($this->categorias as $categoria) {
			if($categoria["no_empty"]){
				$equipo = new Miembros($this->db);
				$equipo->get(1,999,$categoria["categoria_id"]);
				echo '<div class="nosotros-equipo">
					<h2>'.$categoria["categoria_nombre"].'</h2>
					<div class="margin-wrap">';
					for($i=0; $i < count($equipo->miembros); $i++){
						$equipo->miembros[$i]->printMiembro();
					}
				echo '</div>
				</div>';
			}
		}

	}
	public function paginacion($total, $totalresultados, $limit, $pagina){
	    $data = array();
	    $limite1=($pagina-1)*$limit;
	    $data['resultados']='Resultados del '.($limite1+1).' al '.($limite1+$totalresultados);
	    $data['total']=$total . ' disponibles';
		if(isset($_GET['q']) && validaGeneral($_GET['q'],3)){
			$data['busqueda'] = "Búsqueda: '".$_GET['q']."'";
		}else{
			$data['busqueda'] = false;
		}
	    $total_pag=ceil($total/$limit);
	    $l_pag1=$pagina-5;
	    $l_pag2=$pagina+5;
	    if($l_pag1<1){$l_pag1=1;}
	    if($l_pag2>$total_pag){$l_pag2=$total_pag;}

	    $data['limitebajo'] = $l_pag1;
	    $data['limitealto'] = $l_pag2;
		if($data["limitealto"] > 1){
			$this->printPaginacion($data, $pagina);
		}
	}

	public function printPaginacion($paginacion, $paginaactual, $append=''){
	    echo '<div class="paginacion cfix">';
	        echo '<div class="cl">';
	        for($a=$paginacion['limitebajo'];$a<=$paginacion['limitealto'];$a++){
	        	if($paginaactual==$a){
	        		echo '<div class="btn-page active">'.$a.'<span></span></div>';
	        	}else{
	        		echo '<a class="btn-page" href="'.$_GET['dm_currenturl'].'&p='.$a.$append.'">'.$a.'<span></span></a>';
	        	}
	        }
	        echo '</div>';
	        // echo '<div class="cr">';
	        //     echo $paginacion['resultados'].' de '.$paginacion['total'];
	        //     if($paginacion['busqueda']){
	        //         echo '<br>'.$paginacion['busqueda'];
	        //     }
	        // echo '</div>';
		echo '</div>';
	}
}
?>
