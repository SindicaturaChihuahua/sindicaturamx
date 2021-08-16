<?php
class back_amiembros extends Seccion {

	function __construct($db)
	{
		$this->db = $db;
		$this->backclass = "back_amiembros";
		$this->frontclass = "front_amiembros";
        $this->name = "amiembros";
        $this->order = '1';
        $this->version = '1.1.0';
		$this->tab = 'editor';
		$this->displayName = 'Noticias';
		$this->description = 'Descripcion';
		$this->icon = 'edit';
		$this->databaseprefix = 's_amiembros';
		$this->permiso = 'Editor';
		$this->static_urls = array(
			'lista' => 's/'.$this->name,
			'nuevo' => 's/'.$this->name.'/nuevo',
			'editar' => 's/'.$this->name.'/editar',
			'toedit' => 's/'.$this->name.'/toedit',
			'autopublish' => 's/'.$this->name.'/autopublish',
			'nuevo-handler' => 's/'.$this->name.'/nuevo-handler',
			'single-upload-handler' => 's/'.$this->name.'/single-upload-handler',
			'categorias' => 's/'.$this->name.'/categorias',
			'categorias-nuevo' => 's/'.$this->name.'/categorias/nuevo',
			'categorias-editar' => 's/'.$this->name.'/categorias/editar',
			'categorias-nuevo-handler' => 's/'.$this->name.'/categorias/categorias-nuevo-handler',
			'categorias-single-upload-handler' => 's/'.$this->name.'/categorias/categorias-single-upload-handler',
			'categorias-orden-handler' => 's/'.$this->name.'/categorias/categorias-orden-handler',
			'comision' => 's/'.$this->name.'/comision',
			'orden-handler' => 's/'.$this->name.'/orden-handler',
		);
		// Sin uso por ahora, idea: cada tipo tenga sus propios tipos de arhivo
		$this->tipos = array(
			'miembro' => array(
				'nombre' => "Miembro"
			),
			'destacado' => array(
				'nombre' => "Destacado"
			),
		);
		$this->single_files = array(
			'cover' => array(
				'required' => array("jpg","jpge","jpeg","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;Imagen principal',
				'info' => 'Selecciona la imagen principal del miembro',
				'tipoarchivo' => 'image',
				'extra' => false
			),
			'archivo' => array(
				'required' => array("pdf","doc","docx","jpg","jpge","jpeg","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;CV',
				'info' => 'Selecciona el CV del miembro',
				'tipoarchivo' => 'file',
				'extra' => false
			),
			'patrimonial' => array(
				'required' => array("pdf","doc","docx","jpg","jpge","jpeg","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;3de3: Patrimonial',
				'info' => 'Declaración Patrimonial',
				'tipoarchivo' => 'file',
				'extra' => false
			),
			'intereses' => array(
				'required' => array("pdf","doc","docx","jpg","jpge","jpeg","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;3de3: Intereses',
				'info' => 'Declaración de Intereses',
				'tipoarchivo' => 'file',
				'extra' => false
			),
			'fiscal' => array(
				'required' => array("pdf","doc","docx","jpg","jpge","jpeg","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;3de3: Fiscal',
				'info' => 'Declaración Fiscal',
				'tipoarchivo' => 'file',
				'extra' => false
			)
		);
		$this->categorias_single_files = array(
			'categoria_cover' => array(
				'required' => array("jpg","jpge","jpeg","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;Imagen principal',
				'info' => 'Selecciona la imagen principal de la categoría',
				'tipoarchivo' => 'image'
			),
			'categoria_archivo' => array(
				'required' => array("pdf","doc","docx","jpg","jpge","jpeg","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;Archivo Descargable',
				'info' => 'Selecciona el archivo para descargar',
				'tipoarchivo' => 'image'
			)
		);
		$this->estados_comentarios_default='si';
		$this->estados_comentarios = array(
			"si" => "Si",
			"no" => "No"
		);
		$this->configuracion_default = array(
			"formatomiembro" => "miembro"
		);
	}

	public function call($accion,$gama=false,$delta=false) {
		if($accion=="lista"){
			return array(
				"file" => "lista",
			);
		}else if($accion=="toedit"){
			return array(
				"file" => "toedit",
				"ajax" => true
			);
		}else if($accion=="nuevo" || $accion=="editar"){
			if($gama=="miembro"){
				return array(
					"file" => "backend/miembro",
					"css" => array(
						URLPLUGINS.'selectize/selectize.default.css',
						'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css'
					)
				);
			}elseif($gama=="destacado"){
				return array(
					"file" => "backend/destacado",
					"css" => array(
						URLPLUGINS.'selectize/selectize.default.css',
						'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css'
					)
				);
			}
		}else if($accion=="single-upload-handler"){
			return array(
				"file" => "single-upload-handler",
				"ajax" => true
			);
		}else if($accion=="nuevo-handler"){
			if($gama=="miembro"){
				return array(
					"file" => "backend/miembro-nuevo-handler",
					"ajax" => true
				);
			}elseif($gama=="destacado"){
				return array(
					"file" => "backend/destacado-nuevo-handler",
					"ajax" => true
				);
			}
		}else if($accion=="comision"){
			if($gama=="nuevo" || $gama=="eliminar"){
				return array(
					"file" => "ajax/comision",
					"ajax" => true
				);
			}
		}else if($accion=="categorias"){
			if($gama=="nuevo" || $gama=="editar"){
				return array(
					"file" => "categorias/categorias-edit"
				);
			}else if($gama=="categorias-nuevo-handler"){
				return array(
					"file" => "categorias/categorias-nuevo-handler",
					"ajax" => true
				);
			}else if($gama=="categorias-single-upload-handler"){
				return array(
					"file" => "categorias/categorias-single-upload-handler",
					"ajax" => true
				);
			}else if($gama=="categorias-orden-handler"){
				return array(
					"file" => "categorias/categorias-orden-handler",
					"ajax" => true
				);
			}else{
				return array(
					"file" => "categorias/categorias"
				);
			}
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

	public function getPost($id, $tipo=false){
		if(is_numeric($id)){
			if($tipo){
				$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE post_id = ? AND tipo = ? LIMIT 1");
				$stmt->execute(array($id, $tipo));
			}else{
				$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix." WHERE post_id = ? LIMIT 1");
				$stmt->execute(array($id));
			}
			if($row = $stmt->Fetch()){
				$row['extra']=parseJson($row['extra']);
				$row['extra'] = $row['extra'] + $this->configuracion_default;
				return $row;
			}
		}
		return false;
	}

	public function newPost($autor=0, $tipo){
		$stmt = $this->db->prepare("SELECT post_id FROM ".DB_PREFIX.$this->databaseprefix." WHERE autor_id = ? AND tipo = ? AND status = ? LIMIT 1");
		$stmt->execute(array($autor, $tipo, 'revision'));
		$stmt->bindColumn('post_id', $id);
		if($row = $stmt->Fetch()){
			return $id;
		}else{
			$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX.$this->databaseprefix." (autor_id, descripcion, tipo, status, extra) VALUES (?, ?, ?, ?, ?)");
			$stmt->execute(array($autor, '', $tipo, 'revision', ''));
			$post_id = $this->db->lastInsertId();

			//FOLDER
			// $folderfinal=crearDirectorioPorFecha("../public/cargas/".$this->name."/", date("Y-m-d H:i:s"), "obj".$post_id, 0755);

			$folderfinal='obj'.$post_id.'/';
			crearDirectorio("../public/cargas/".$this->name."/obj".$post_id);
			$stmt = $this->db->prepare("UPDATE ".DB_PREFIX.$this->databaseprefix." SET folder = ? WHERE post_id = ?");
			$stmt->execute(array($folderfinal, $post_id));

			return $post_id;
		}
	}

	public function getPost_Categorias($id){
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_relation WHERE post_id = ?");
		$stmt->execute(array($id));
		if($res = $stmt->FetchAll()){
			return $res;
		}
		return array();
	}

	public function get_Categorias(){
		$categorias=array();
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_categorias ORDER by orden ASC");
		$stmt->execute();
		if($categoriastmp = $stmt->FetchAll()){
			foreach($categoriastmp as $categoria){
				$categorias[$categoria['categoria_id']]=$categoria;
			}
		}
		return $categorias;
	}

	public function getCategoria($id){
		if(is_numeric($id)){
			if($stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX.$this->databaseprefix."_categorias WHERE categoria_id = ? LIMIT 1")){
				$stmt->execute(array($id));
				$res = $stmt->Fetch();
				return $res;
			}
		}
		return false;
	}

	public function newCategoria(){
		if($stmt = $this->db->prepare("SELECT categoria_id FROM ".DB_PREFIX.$this->databaseprefix."_categorias WHERE status = ? LIMIT 1")){
			$stmt->execute(array('revision'));
			$stmt->bindColumn('categoria_id', $id);
			if($row = $stmt->Fetch()){
				return $id;
			}else{
				$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX.$this->databaseprefix."_categorias (status) VALUES (?)");
				$stmt->execute(array('revision'));
				$categoria_id = $this->db->lastInsertId();
				return $categoria_id;
			}
		}
	}

	public function existsUniqueSlugCategoria($slug,$id){
		if($stmt = $this->db->prepare("SELECT categoria_id FROM ".DB_PREFIX.$this->databaseprefix."_categorias WHERE categoria_slug = ? LIMIT 1")){
			$stmt->execute(array($slug));
			$stmt->bindColumn('categoria_id',$oid);
			if($stmt->fetch()){
				if($oid!=$id){
					return true;
				}
			}
		}
		return false;
	}

	public function existsUniqueSlug($slug,$id){
		if($stmt = $this->db->prepare("SELECT post_id FROM ".DB_PREFIX.$this->databaseprefix." WHERE slug = ? LIMIT 1")){
			$stmt->execute(array($slug));
			$stmt->bindColumn('post_id',$oid);
			if($stmt->Fetch()){
				if($oid!=$id){
					return true;
				}
			}
		}
		return false;
	}

	public function generateUniqueSlug($slug){
		$slugF=$slug."-".rand(99,9999);
		return $slugF;
	}

	public function nuevo_categoriasFinales($actuales,$nuevas){
		$categorias_finales=array();
		$categorias_eliminar=array();

		if(!empty($nuevas)){
			foreach($nuevas as $s_c){
				$found=false;
				for($a=0;$a<count($actuales);$a++){
					if($actuales[$a]['categoria_id']==$s_c){
						$actuales[$a]['found']=true;
						$found=true;
						break;
					}
				}
				if(!$found){
					$categorias_finales[]=array($s_c,'insertar');
				}
			}
		}
		for($a=0;$a<count($actuales);$a++){
			if(!isset($actuales[$a]['found'])){
				$categorias_eliminar[]=$actuales[$a]['categoria_id'];
			}else{
				$categorias_finales[]=array($actuales[$a]['categoria_id'],'mantener');
			}
		}
		return array(
			'finales'=>$categorias_finales,
			'eliminar'=>$categorias_eliminar
		);
	}

	public function nuevo_insertarcategoriasFinales($post_id,$finales){
		foreach($finales['finales'] as $cn){
			if($cn[1]=='insertar'){
				$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX.$this->databaseprefix."_relation (post_id, categoria_id) VALUES (?, ?)");
				$stmt->execute(array($post_id, $cn[0]));
			}
		}
		foreach($finales['eliminar'] as $ce){
			$stmt = $this->db->prepare("DELETE FROM ".DB_PREFIX.$this->databaseprefix."_relation WHERE post_id = ? AND categoria_id = ?");
			$stmt->execute(array($post_id,$ce));
		}
	}

	public function get_Usuarios(){
		$categorias=array();
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users ORDER BY pseudonimo ASC");
		$stmt->execute();
		$categorias = $stmt->FetchAll();
		return $categorias;
	}


	public function getComisiones(){
		$comisiones=array();
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."s_acomisiones ORDER by titulo ASC");
		$stmt->execute();
		if($comisionestmp = $stmt->FetchAll()){
			foreach($comisionestmp as $comision){
				$comisiones[$comision['post_id']]=$comision;
			}
		}
		return $comisiones;
	}


	public function getDatas($mid, $tabla, $campo="miembro_id", $order="DESC"){
		$stmt = $this->db->prepare("SELECT t1.* FROM ".DB_PREFIX.$this->databaseprefix."_".$tabla." AS t1 WHERE t1.miembro_id = ? AND t1.status = ? ORDER BY t1.orden ASC, t1.modificado ASC LIMIT 100");
		$stmt->execute(array($mid, 'publico'));
		if($rows = $stmt->fetchAll()){
			return $rows;
		}
	}

	public function getObject($id, $campo, $tabla){
		if(is_numeric($id)){
			$stmt = $this->db->prepare("SELECT t1.* FROM ".DB_PREFIX.$this->databaseprefix."_".$tabla." AS t1 WHERE t1.".$campo." = ? AND t1.status = ? LIMIT 1");
			$stmt->execute(array($id, 'publico'));
			if($row = $stmt->Fetch()){
				return $row;
			}
		}
		return false;
	}

	public function newObject($mid, $tabla){
		$stmt = $this->db->prepare("SELECT miembro_id FROM ".DB_PREFIX.$this->databaseprefix."_".$tabla." WHERE miembro_id = ? AND status = ? LIMIT 1");
		$stmt->execute(array($mid, 'revision'));
		$stmt->bindColumn('miembro_id', $id);
		if($row = $stmt->Fetch()){
			return $id;
		}else{
			$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX.$this->databaseprefix."_".$tabla." (miembro_id, status, modificado) VALUES (?, ?, ?)");
			$stmt->execute(array($mid, 'publico', date("Y-m-d H:i:s")));
			$mid = $this->db->lastInsertId();
			return $mid;
		}
	}

	public function templateElpaso($obj=false, $deleteurl="",$comisiones = false){
		if($obj){
			$options = '';
			$puestos = array(
				"Presidenta" => "Presidenta",
				"Presidente" => "Presidente",
				"Secretaria" => "Secretaria",
				"Secretario" => "Secretario",
				"Vocal" => "Vocal",
			);
			foreach($puestos as $puesto => $texto){
				if($obj['comision_puesto'] == $puesto){
					$options .= '<option value="'.$puesto.'" selected>'.$texto.'</option>';
				}else{
					$options .= '<option value="'.$puesto.'">'.$texto.'</option>';
				}
			}
			if(!$comisiones){
				$comisiones = $this->getComisiones();
			}
			foreach($comisiones as $comision){
				if($obj['comision_asignada'] == $comision["post_id"]){
					$optionsC .= '<option value="'.$comision["post_id"].'" selected>'.$comision["titulo"].'</option>';
				}else{
					$optionsC .= '<option value="'.$comision["post_id"].'">'.$comision["titulo"].'</option>';
				}
			}

			echo '<div class="anelm" id="elpasoobj-'.$obj['comision_id'].'">';
			echo '<div class="handle"><div class="hicon"><i class="fa fa-sort" aria-hidden="true"></i></div></div>';
			echo '<input type="hidden" name="comision_id[]" value="'.$obj['comision_id'].'">';
			echo '<input type="hidden" name="comision_orden[]" value="">';
			echo '<div class="aeacts"><a href="'.$deleteurl.$obj['comision_id'].'?action=delete-object" class="btn btn-danger btn-xs delete-object-btn">Eliminar</a></div>';
			echo '<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label class="control-label">Comisión</label>
						<select class="form-control" name="comision_asignada[]">
							'.$optionsC.'
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Puesto</label>
						<select class="form-control" name="comision_puesto[]">
							'.$options.'
						</select>
					</div>
				</div>
				<div class="col-sm-4">
				</div>
			</div>';
			echo '</div>';
		}
	}

}
?>
