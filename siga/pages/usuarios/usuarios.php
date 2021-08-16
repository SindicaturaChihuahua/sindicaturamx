<?php
class back_usuarios extends Seccion {

	function __construct($db)
	{
		$this->db = $db;
        $this->name = "usuarios";
        $this->order = '1';
        $this->version = '1.1.0';
		$this->tab = 'administracion';
		$this->displayName = 'Usuarios';
		$this->description = 'Usuarios';
		$this->icon = 'users';
		$this->databaseprefix = 'users';
		$this->permiso = 'ManejarUsuarios';
		$this->static_urls = array(
			'lista' => 'p/'.$this->name,
			'nuevo' => 'p/'.$this->name.'/nuevo',
			'editar' => 'p/'.$this->name.'/editar',
			'nuevo-handler' => 'p/'.$this->name.'/nuevo-handler',
			'single-upload-handler' => 'p/'.$this->name.'/single-upload-handler'
		);
		$this->single_files=array(
			'cover' => array(
				'required' => array("jpg","jpge","gif","png"),
				'maxfilesize' => '10',
				'info_btn' => '<span class="fa fa-file-image-o"></span> &nbsp;Imagen principal',
				'info' => 'Selecciona la imagen de perfil para este usuario',
				'tipoarchivo' => 'image'
			)
		);
		$this->permisos = array(
			50 => array(
				"permiso" => "",
				"titulo" => "Ninguno (sin acceso)",
				"nivel" => 50,
				"descripcion" => "Sin acceso al sistema",
				"obligatorios" => "",
				"recomendados" => ""
			),
			51 => array(
				"permiso" => "Usuario",
				"titulo" => "Usuario (Staff)",
				"nivel" => 51,
				"descripcion" => "Acceso básico al sistema.",
				"obligatorios" => "",
				"recomendados" => ""
			),
			60 => array(
				"permiso" => "Editor",
				"titulo" => "Editor",
				"nivel" => 60,
				"descripcion" => "Permite crear, editar y eliminar contenido del sitio haciendo uso del editor de contenido.",
				"obligatorios" => "Usuario",
				"recomendados" => ""
			),
			65 => array(
				"permiso" => "EditorEnJefe",
				"titulo" => "Editor Jefe",
				"nivel" => 65,
				"descripcion" => "Permite crear, editar y eliminar contenido del sitio de todos los usuarios.",
				"obligatorios" => "Usuario",
				"recomendados" => ""
			),
			80 => array(
				"permiso" => "Administracion",
				"titulo" => "Administracion",
				"nivel" => 80,
				"descripcion" => "Acceso a funciones avanzadas del sistema.",
				"obligatorios" => "Usuario",
				"recomendados" => ""
			),
			85 => array(
				"permiso" => "ManejarUsuarios",
				"titulo" => "Manejar Usuarios",
				"nivel" => 85,
				"descripcion" => "Manipular permisos de usuarios.",
				"obligatorios" => "Usuario",
				"recomendados" => "Administracion"
			),
			100 => array(
				"permiso" => "TheBoss",
				"titulo" => "The Boss",
				"nivel" => 100,
				"descripcion" => "Manipular permisos de usuarios.",
				"obligatorios" => "",
				"recomendados" => ""
			)
		);
	}

	public function call($accion,$gama=false,$delta=false) {
		if($accion=="lista"){
			return array(
				"file" => "lista",
			);
		}else if($accion=="nuevo" || $accion=="editar"){
			return array(
				"file" => "edit",
				"css" => array(
					URLPLUGINS.'selectize/selectize.default.css'
				)
			);
		}else if($accion=="single-upload-handler"){
			return array(
				"file" => "single-upload-handler",
				"ajax" => true
			);
		}else if($accion=="nuevo-handler"){
			return array(
				"file" => "nuevo-handler",
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
}
?>
