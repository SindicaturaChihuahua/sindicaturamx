<?php
$response_json=true;
$errores=array();
$data=array();
$message="";
$rawhtml="";
$mustlogin=true;
$reload="";

$mdcontenido = "";
if(isset($_POST['action']) && $_POST['action'] == "sendemail" && isset($_POST['paginareferencia'])){

    if(!isset($_POST['correo']) || !validaMail($_POST['correo'])){
        $errores[]=__('bad_email_format');
    }else{
        $mdcontenido .= "<p>Correo electrónico: ".$_POST['correo']."</p>";
    }

    if(empty($errores)){
        $mdcontenido .= "<p>Comisión: ".$_POST['paginareferencia']."</p>";

        $fecha = date("Y-m-d H:i:s");
        $stmt = $db->prepare("INSERT INTO ".DB_PREFIX."s_registros (correo, comision_id, modificado) VALUES (?, ?, ?)");
        $stmt->execute(array($_POST['correo'],$_POST['id'],$fecha));

        require_once DMINCLUDES . 'htmlpurifier-4.6.0-standalone/HTMLPurifier.standalone.php';
        $mdcontenido = clean_input_purify($mdcontenido);

        try {
          require_once DMINCLUDES . 'Mandrill.php';
          $mandrill = new Mandrill('CgXNxtaKROhqZO72upufTw');
          $template_content = array();
          $mdmessage = array(
            'subject' => 'Nuevo Registro '.$opcionesfull['opciones']['site_title'],
            'from_email' => $_POST['correo'],
            'from_name' => $_POST['nombre'],
            'to' => array(
              array(
                'email' => $opcionesfull['opciones']['contacto_email'],
                'name' => $opcionesfull['opciones']['contacto_nombre']
			  ),
			  array(
				  'email' => 'marco.loya@mpiochih.gob.mx'
			  ),
			  array(
				  'email' => 'luis.moreno@mpiochih.gob.mx'
			  )
            ),
            'global_merge_vars' => array(
              array(
                'name' => 'CONTENIDO',
                'content' => $mdcontenido
              )
            ),
            'tags' => array('contacto', crea_amigableUrl($opcionesfull['opciones']['site_title'],20)),
            'subaccount' => 'general',
          );
          $async = false;
          $result = $mandrill->messages->sendTemplate('basico-general', $template_content, $mdmessage, $async);

          $message = "Tu registro ha sido realizado con éxito. Pronto empezarás a recibir información sobre esta comisión.";
        } catch(Mandrill_Error $e) {
          $errores[] = "Error al enviar el email.";
        }

    }
}

$controlador->response( $data, $rawhtml, $message, $errores, 200, $mustlogin, $reload );
?>
