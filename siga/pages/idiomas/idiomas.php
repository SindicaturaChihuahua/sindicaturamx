<?php
class back_idiomas
{

    public function __construct($db) {
		$this->permiso = 'Administracion';
    }

    public function call($accion) {
		if($accion=="lista"){
			return array(
				"file" => "lista",
				"js" => array(

				)
			);
		}else if($accion=="acciones"){
			return array(
				"file" => "acciones"
			);
		}
    }
}
?>
