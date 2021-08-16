<?php
class back_archivos extends Seccion {

	function __construct($db)
	{
		$this->db = $db;
        $this->name = "archivos";
        $this->order = '1';
        $this->version = '1.0';
		$this->tab = 'editor';
		$this->displayName = 'Archivos';
		$this->description = 'Descripcion';
		$this->icon = 'paperclip';
		$this->databaseprefix = 's_archivosgenerales';
		$this->permiso = 'Editor';
		$this->static_urls = array(
			'lista' => 'p/'.$this->name,
		);
	}

	public function call($accion,$gama=false,$delta=false) {
		if($accion=="lista"){
			return array(
				"file" => "lista",
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
