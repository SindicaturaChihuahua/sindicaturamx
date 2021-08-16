<?php
class back_secciones
{

    public function __construct($db) {
		$this->permiso = 'Folklore';
    }

    public function call($accion) {
		if($accion=="lista"){
			return array(
				"file" => "lista"
			);
		}
    }
}
?>
