<?php
//Actualizado: Abril 4 2018
$tiempo=array(
	'month' => array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiempre", "Octubre", "Noviembre", "Diciembre"),
	'monthmini' => array("","Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"),
	'day' => array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"),
	'daymini' => array("Dom","Lun","Mar","Mie","Jue","Vie","Sab")
);

function __($name) {
    global $lang;
    return $lang[$name];
}

function saveOpcionesJSON(){
	$opciones = getOpciones();
	$otras_opciones = getOtrasOpciones();
	$final=array(
		'opciones' => $opciones,
		'otras_opciones' => $otras_opciones
	);
	$folder = ROOT.'private/';
	crearDirectorio($folder);
	if(file_exists($folder.'dm_opciones.json')){
		if($fh = fopen($folder.'dm_opciones.json', "w")){
			if(fwrite($fh, json_encode($final))){
				fclose($fh);
				return true;
			}
		}
	}
	cerrarDirectorio($folder);
	return false;
}

function dataMapa($data){
    $default = array(
        'map_lat' => 28.6329957,
        'map_long' => -106.06910040000002,
        'mostrar' => 0
    );
    $data = json_decode($data, true);
    $data = is_array($data) ? $data : array();
    $final = $data + $default;

    return $final;
}

function fechayhoraActual(){
	global $tiempo;
	$tiempoactual=time();
	$tiempoactual-=(TIMEDIF*3600);
	$dia=date('j',$tiempoactual);
	$diaSemana=date('w',$tiempoactual);
	$mes=date('n',$tiempoactual);
	$ano=date('Y',$tiempoactual);
	return $tiempo['day'][$diaSemana]." ".$dia." de ".$tiempo['month'][$mes]." de ".$ano.", ".date("g:i a",$tiempoactual);
}

function getFechaActualConDiferencia(){
	$tiempoactual=time();
	$tiempoactual-=(TIMEDIF*3600);
	return date("Y-m-d H:i:s",$tiempoactual);
}
function getFechaActualSinDiferencia(){
	return date("Y-m-d H:i:s");
}

function formatPrecio($precio, $decimales = 2, $prefix = '$', $sufijo = "MXN"){
	$precio = number_format($precio, $decimales);
	return $prefix."".$precio." ".$sufijo;
}

function cleanWhiteSpaces($nombre){
	$result = preg_replace('/\s+/', ' ', trim($nombre));

	return $result;
}

function createPseudonimo($nombre, $words=2){
	$result = explode(" ",$nombre);
	$count_words = count($result);
	if($count_words>=4){
		$nombre = $result[0].' '.$result[2];
	}else if($count_words==3){
		$nombre = $result[0].' '.$result[1];
	}

	return $nombre;
}

function getFirstName($nombre){
	$result = explode(" ",$nombre);

	return $result[0];
}

function enRango($numero, $limitebajo, $limitealto=999000000){
	if(is_numeric($numero)){
		if($numero >= $limitebajo && $numero <= $limitealto){
			return true;
		}
	}
	return false;
}

function get_url($url) {
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}

function getArbolObjs($arbol){
	$menu = $arbol;
	$finales = array();
	if($menu){
		foreach($menu as $s => $elementos){
			foreach ($elementos as $key => $value) {
				$finales[]=$value;
			}
		}
		$finales = array_unique($finales);
	}

	return $finales;
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function fechaReal($string){
	if(preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", $string)){
		return true;
	}
	return false;
}

function datetimeReal($string){
	if(preg_match("/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/", $string)){
		return true;
	}
	return false;
}

function datetimepickerFormatDate($fecha,$formato="Y-m-d H:i:s"){
	if($fecha!='0000-00-00 00:00:00' && $fecha!='0000-00-00'){
		$lafecha=strtotime($fecha);
		$lafecha-=(TIMEDIF*3600);
		$lafecha=date($formato,$lafecha);
		return $lafecha;
	}
	return '';
}

function addDiffFormatDate($fecha,$formato="Y-m-d H:i:s"){
	if($fecha!='0000-00-00 00:00:00' && $fecha!='0000-00-00'){
		$lafecha=strtotime($fecha);
		$lafecha+=(TIMEDIF*3600);
		$lafecha=date($formato,$lafecha);
		return $lafecha;
	}
	return '';
}

function parseJson($data){
	$parse=array();
	if($data!=''){
		$data=json_decode($data,true);
		if(!empty($data)){
			$parse=$data;
		}
	}
	return $parse;
}

function getFolderDate($add='/'){
	$mes=strtolower(date("F",time()));
	$ano=date("Y",time());
	$dia=date("d",time());
	$folder=$ano.'/'.$mes."/".$dia;
	if($add){
		$folder=$ano.'/'.$mes."/".$dia.$add;
	}
	return $folder;
}

function pdoSet($fields, &$values, $source = array()) {
	$set = '';
	$values = array();
	if (!$source) $source = &$_POST;
		foreach ($fields as $field) {
			if (isset($source[$field])) {
				$set.="`$field`=:$field, ";
				$values[$field] = $source[$field];
			}
		}
	return substr($set, 0, -2);
}

function getInstalledSecciones(){
	global $db;
	$data=array();
	if($result = $db->query("SELECT * FROM ".DB_PREFIX."secciones ORDER BY orden ASC")){
		while($opt = $result->Fetch()){
			$data[$opt['nombre']]=$opt;
		}
	}
	return $data;
}

function c_ErrorSession($data){
	if(count($data)){
		if(isset($_SESSION["error"])){
			$_SESSION["error"]=array_merge($_SESSION["error"],$data);
		}else{
			$_SESSION["error"]=$data;
		}
	}
}

function c_OkeySession($data){
	if(count($data)){
		if(isset($_SESSION["okey"])){
			$_SESSION["okey"]=array_merge($_SESSION["okey"],$data);
		}else{
			$_SESSION["okey"]=$data;
		}
	}
}

function c_DelMessageSession(){
	unset($_SESSION["okey"]);
	unset($_SESSION["error"]);
}

function limpiar_find($search){
	$original=str_replace(array('á','é','í','ó','ú','Á','É','Í','Ó','Ú'), array('a','e','i','o','u','A','E','I','O','U'), $search);
	$search = (ini_get('magic_quotes_gpc')) ? stripslashes($original) : $original;
	return $search;
}

function array2csv(array &$array){
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function c_PrintMessages(){
	if(isset($_SESSION['error'])){
		echo '<div class="alert alert-danger">';
			echo implode("<br>",$_SESSION['error']);
		echo '</div>';
	}
	if(isset($_SESSION['okey'])){
		echo '<div class="alert alert-info">';
			echo implode("<br>",$_SESSION['okey']);
		echo '</div>';
	}
	c_DelMessageSession();
}

function getTwitterProfilePicture($ppurl, $uid, $prepath="./public/"){
	global $db;
	if(strlen($ppurl)>5){
		$picture=str_replace("_normal","",$ppurl);
		$ext = substr(strrchr($picture, "."), 1);
		if(in_array($ext,array("jpg","jpe","jpeg","gif","png"))){
			$img = file_get_contents($picture);
			if($img){
				$newfile = 'ppt-'.$uid.'_'.crearCadena(4).'.'.$ext;
				$folderfinal=$prepath."pages/usuarios/obj".$uid."/";
				crearDirectorio($folderfinal);
				if(file_put_contents($folderfinal.$newfile, $img)){
					exportimg($folderfinal.$newfile, $folderfinal."big_".$newfile, 600, 600, 88, 0, 1);
					exportimg($folderfinal.$newfile, $folderfinal."medium_".$newfile, 600, 600, 86, 0, 1);
					exportimg($folderfinal.$newfile, $folderfinal."small_".$newfile, 140, 140, 84, 0, 1);
					exportimg($folderfinal.$newfile, $folderfinal."xsmall_".$newfile, 60, 60, 82, 0, 1);
					$stmt = $db->prepare("UPDATE ".DB_PREFIX."users SET cover = :cover WHERE uid = :theobjid");
					$stmt->execute(array(":cover"=>$newfile,":theobjid"=>$uid));
				}
				cerrarDirectorio($folderfinal);
			}
		}
	}
}

function getFacebookProfilePicture($facebookuid, $uid){
	global $db;
	$picture='http://graph.facebook.com/'.$facebookuid.'/picture?height=600&width=600';
	$arrContextOptions=array(
    	"ssl"=>array(
	        "verify_peer"=>false,
	        "verify_peer_name"=>false,
	    ),
	);
	$img = file_get_contents($picture, false, stream_context_create($arrContextOptions));
	if($img){
		$newfile = crearCadena(8).'.jpg';
		$folderfinal="./public/pages/usuarios/obj".$uid."/";
		crearDirectorio($folderfinal);
		if(file_put_contents($folderfinal.$newfile, $img)){
			exportimg($folderfinal.$newfile, $folderfinal."big_".$newfile, 600, 600, 88, 0, 1);
			exportimg($folderfinal.$newfile, $folderfinal."medium_".$newfile, 600, 600, 86, 0, 1);
			exportimg($folderfinal.$newfile, $folderfinal."small_".$newfile, 140, 140, 84, 0, 1);
			exportimg($folderfinal.$newfile, $folderfinal."xsmall_".$newfile, 60, 60, 82, 0, 1);
			$stmt = $db->prepare("UPDATE ".DB_PREFIX."users SET cover = :cover WHERE uid = :theobjid");
			$stmt->execute(array(":cover"=>$newfile,":theobjid"=>$uid));
		}
		cerrarDirectorio($folderfinal);
	}
}

function getOpcionesFull($from='cache', $pathtoroot='./'){
	$data=array();
	if($from=='cache'){
		$data = json_decode(file_get_contents($pathtoroot.'private/dm_opciones.json'), true);
	}
	if(empty($data)){
		$data=array(
			'opciones' => getOpciones(),
			'otras_opciones' => getOtrasOpciones()
		);
	}
	return $data;
}

function getOpciones(){
	global $db;
	$data=array();
	if($result = $db->query("SELECT * FROM ".DB_PREFIX."opciones")){
		while($opt = $result->Fetch()){
			$data[$opt['opcion_nombre']]=$opt['opcion_valor'];
		}
	}
	return $data;
}

function getOtrasOpciones(){
	global $db;
	$data=array();
	if($result = $db->query("SELECT * FROM ".DB_PREFIX."otras_opciones")){
		while($opt = $result->Fetch()){
			$data[$opt['oo_nombre']]=array(
				'oo_valor' => $opt['oo_valor'],
				'oo_adicional1' => $opt['oo_adicional1']
			);
		}
	}
	return $data;
}

function emailExists($email){
	global $db;
	$stmt = $db->prepare("SELECT uid, email FROM ".DB_PREFIX."users WHERE email = ? LIMIT 1");
	$stmt->execute(array($email));
	$results=$stmt->Fetch();
	if(!empty($results)){
		return true;
	}
	return false;
}

function generateNewUsername($username){
	$usernameF=$username."_".rand(99,9999);
	return $usernameF;
}

function usernameExists($username){
	global $db;
	$stmt = $db->prepare("SELECT uid FROM ".DB_PREFIX."users WHERE username = ? LIMIT 1");
	$stmt->execute(array($username));
	$results=$stmt->Fetch();
	if(!empty($results)){
		return true;
	}
	return false;
}

function getFromMultiArray($field,$query,$array){
	$index=null;
	if($array){
		foreach($array as $i => $arr){
			if(isset($arr[$field]) && $arr[$field]==$query){
				$index=$i;
				break;
			}
		}
	}
	return $index;
}

function nombreValido($nombre,$minimo,$maximo){
   if(preg_match("/^[a-zA-Z0-9][ a-zA-Z0-9]{".$minimo.",".$maximo."}$/", $nombre)){
      return true;
   }else{
      return false;
   }
}

function passwordValido($pass){
   if(preg_match("/^[\._a-zA-Z0-9@*()&+$-]{6,20}$/", $pass)){
      return true;
   }else{
      return false;
   }
}

function usernameValido($username){
   if(preg_match("/^[a-zA-Z0-9][a-zA-Z0-9]{3,25}$/", $username)){
   		if(!tieneMalasPalabras($username)){
      		return true;
  		}
   }else{
      return false;
   }
}

function tieneMalasPalabras($str) {
	$badwords=array('referente', 'putita');
    foreach ($badwords as $word) {
        if (stripos(" $str ", " $word ") !== false) {
            return true;
        }
    }
    return false;
}

function urlamigaValida($amiga){
   if(preg_match("/^[-_a-zA-Z0-9]{2,225}$/", $amiga)){
      return true;
   }else{
      return false;
   }
}

function crearId(){
	$idunico = md5(uniqid());
	return $idunico;
}

function generateFormToken($form) {
   $token = md5(uniqid(microtime(), true));
   $token_time = time();
   $_SESSION['csrf'][$form.'_token'] = array('token'=>$token, 'time'=>$token_time);;
   return $token;
}

function verifyFormToken($form, $token, $delta_time=0) {
   if(!isset($_SESSION['csrf'][$form.'_token'])) {
       return false;
   }
   if ($_SESSION['csrf'][$form.'_token']['token'] !== $token) {
       return false;
   }
   if($delta_time > 0){
       $token_age = time() - $_SESSION['csrf'][$form.'_token']['time'];
       if($token_age >= $delta_time){
      return false;
       }
   }
   return true;
}

function cleanOutput($output){
	return htmlentities(stripslashes($output), ENT_QUOTES, 'UTF-8');
}

function limpiarSalida($salida){
	return strip_tags($salida);
}

function clean_input_purify($data, $iframesafe=true){
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Trusted', true);

	if($iframesafe){
		$config->set('HTML.SafeIframe', true);
	}
	$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');

	$purifier = new HTMLPurifier($config);
	$temp = $purifier->purify($data);
	return $temp;
}

function clean_input($input) {
	$search = array(
		'@<script[^>]*?>.*?</script>@si',
		'@<style[^>]*?>.*?</style>@siU',
		'@<![\s\S]*?--[ \t\n\r]*>@'
	);

	$output = preg_replace($search, '', $input);
	return $output;
}

function limpiarCampo($campo){
	return trim($campo);
}

function limpiarCampos($array){
	if(!empty($array)){
		foreach($array as $data => $valor){
			if(is_array($array[$data]) && !empty($array[$data])){
				$array[$data]=limpiarCampos($array[$data]);
			}else{
				$array[$data]=limpiarCampo($array[$data]);
			}
		}
	}
	return $array;
}

function nombreCorto($nombres){
	if(strlen($nombres)>1){
		$nombres=explode(' ',$nombres);
		return $nombres[0];
	}
}

function crearCadena($tamano){
	$str = "abcdefghijklmnopqrstuvwxyz1234567890ABCD";
	$cad = "";
	for($i=0;$i<$tamano;$i++) {
		$cad .= substr($str,rand(0,38),1);
	}
	return $cad;
}

function validateDate($date, $format = 'Y-m-d H:i:s'){
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}

function validateURL($url){
	return filter_var($url, FILTER_VALIDATE_URL);
}

function formatDateSpanish($tipo='month',$elm){
	global $tiempo;
	return $tiempo[$tipo][$elm];
}

function formatDate($date, $format = 'default'){
	$tiempo=strtotime($date);
	if($format=='default'){
		$format='M j, Y';
	}else if($format=='tipo1'){
		$format='F j, Y';
	}else if($format=='siga'){
		$format = formatDateSpanish('monthmini',date('n', $tiempo)) .' '. date('j, Y - g:i:s a',$tiempo);
		return $format;
	}else if($format=='detalle'){
		$format='D, M j, Y';
	}else if($format=='formal'){
		$format=date('j', $tiempo).' de '. formatDateSpanish('month',date('n', $tiempo)) .' de '. date('Y',$tiempo);
		return $format;
	}else if($format=='front'){
		$format = formatDateSpanish('monthmini',date('n', $tiempo)) .' '. date('j, Y',$tiempo);
		return $format;
	}else if($format=='indicador'){
		$format=date('j', $tiempo).' de '.formatDateSpanish('month',date('n', $tiempo)) .' '. date('Y',$tiempo);
		return $format;
	}else if($format=='indicador_noyear'){
		$format=date('j', $tiempo).' de '.formatDateSpanish('month',date('n', $tiempo));
		return $format;
	}else if($format=='hora'){
		$format=date('G:i',$tiempo);
		return $format;
	}else if($format == 'year'){
		$format = 'Y';
	}
	return date($format,$tiempo);
}

function validaMail($pMail){
	if (!filter_var($pMail, FILTER_VALIDATE_EMAIL)) {
		// invalid emailaddress
		return false;
	}
	return true;
}

function validaGeneral($string,$min=0,$max=50000000){
	$string=trim($string);
	if(strlen($string)<=$max && strlen($string)>=$min){
		return true;
	}
	return false;
}

function password_random(){
 $r = '';
 for($i=0; $i<14; $i++)
 $r .= chr(rand(0, 25) + ord('a'));
 return $r;
}

/* Registro y Acceso */

function hashPassword($pPassword){
	return md5($pPassword);
}

function logeado(){
	if(isset($_SESSION['logeado']) && $_SESSION['logeado']==true && isset($_SESSION['uid']) && is_numeric($_SESSION['uid'])){
		return true;
	}
	return false;
}

function logout(){
	unset($_SESSION['uid']);
	unset($_SESSION['username']);
	unset($_SESSION['nombre']);
	unset($_SESSION['status']);
	unset($_SESSION['logeado']);
	unset($_SESSION);
	return true;
}

function createCookies($pEmail){
	$newhash=crearCadena(30);
	$string=$pEmail.'||'.$newhash;
	$string=encrypt($string,DM_HASH_KEY);
	setcookie('dmci', $string, time()+260000, '/');
	return $newhash;
}

function deleteCookies(){
	setcookie('dmci', '', time()-8600, '/');
}

function get_Cookies(){
	if(isset($_COOKIE['dmci'])){
		$args = array();
		$string=decrypt($_COOKIE['dmci'],DM_HASH_KEY);
		$args = explode('||',trim($string));
		deleteCookies();
		return $args;
	}else{
		return null;
	}
}

function encrypt($string, $key) {
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}

function decrypt($string, $key) {
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
}

function haceCuanto($tiempo){
	global $tactual;
	$unaSemana=604800;
	$unDia=86400;
	$unaHora=3600;
	$unMinuto=60;
	$tr=$tactual-$tiempo;
	if($tr>=($unaSemana*4)){ //>= a 4 semanas
		return date('M j, Y',$tiempo);
	}else if($tr>=($unaSemana*2)){ //>= a 2 semanas
		return floor($tr/$unaSemana)." semanas atrás";
	}else if($tr>=$unaSemana){ //>= a 1 semana
		return floor($tr/$unaSemana)." semana atrás";
	}else if($tr>=($unDia*2)){ //>= a 2 dias
		return floor($tr/$unDia)." días atrás";
	}else if($tr>=$unDia){ //>= a 1 dia
		return floor($tr/$unDia)." día atrás";
	}else if($tr>=7200){ //>= a 2 horas
		return floor($tr/$unaHora)." horas atrás";
	}else if($tr>=3600){ //>= a 1 hora
		return floor($tr/$unaHora)." hora atrás";
	}else if($tr>=120){ //>= a 2 minutos
		return floor($tr/$unMinuto)." minutos atrás";
	}else if($tr>=60){ //>= a 1 minutos
		return floor($tr/$unMinuto)." minuto atrás";
	}else{
		return "";
	}
}

function truncar_cadena($string, $limit, $break=".", $pad="..."){
	$string=trim($string);
	if(strlen($string) <= $limit){
		return $string;
	}
	if(false !== ($breakpoint = strpos($string, $break, $limit))){
		if($breakpoint<(strlen($string)-1)){
			$string = substr($string, 0, $breakpoint) . $pad;
		}
	}
	return $string;
}

function crearDirectorio($directory,$extra=false,$perm=0777){
 if(!file_exists($directory)){
 	if(mkdir($directory,$perm)){
 	// 	chmod($directory, $perm);
 	}
 }else{
 // 	 chmod($directory, $perm);
 }
 if($extra!==false){
	crearDirectorio($directory.$extra."/");
 }
}

function cerrarDirectorio($directory,$extra=false,$perm=0755){
 if($extra!==false){
	cerrarDirectorio($directory.$extra."/");
 }
 if(file_exists($directory)){
 // 	chmod($directory, 0755);
 }
}

function crearDirectorioPorFecha($path='./', $fecha, $add='', $permiso=0777){
	$fecha = strtotime($fecha);
	$ano=date("Y",$fecha);
	$mes=strtolower(date("M",$fecha));
	$dia=date("d",$fecha);
	$folder=$ano.'/';
	crearDirectorio($path.$folder, false, $permiso);
	$folder.=$mes.'/';
	crearDirectorio($path.$folder, false, $permiso);
	$folder.=$dia.'/';
	crearDirectorio($path.$folder, false, $permiso);
	if($add!=''){
		$folder.=$add.'/';
		crearDirectorio($path.$folder, false, $permiso);
	}
	return $folder;
}

function cerrarDirectorioPorFecha($path='./', $fecha, $add='', $permiso=0777){
	$fecha = strtotime($fecha);
	$ano=date("Y",$fecha);
	$mes=strtolower(date("M",$fecha));
	$dia=date("d",$fecha);
	if($add!=''){
		$folder=$ano.'/'.$mes.'/'.$dia.'/'.$add.'/';
		cerrarDirectorio($path.$folder, false, $permiso);
	}
	$folder=$ano.'/'.$mes.'/'.$dia.'/';
	cerrarDirectorio($path.$folder, false, $permiso);
	$folder=$ano.'/'.$mes.'/';
	cerrarDirectorio($path.$folder, false, $permiso);
	$folder=$ano.'/';
	cerrarDirectorio($path.$folder, false, $permiso);
	return true;
}

function deleteFiles($filename, $path, $versions=array()){
	$versions[]="";
	if(!empty($versions) && strlen($filename)>1){
		foreach($versions as $version){
			if( file_exists( $path . $version . $filename ) && is_writable( $path . $version . $filename ) ){
				unlink( $path . $version . $filename );
			}
		}
		return true;
	}
	return false;
}

function crea_amigableUrl($url,$maxsize=0){
	$url = strtolower($url);
	$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	$repl = array('a', 'e', 'i', 'o', 'u', 'n');
	$url = str_replace ($find, $repl, $url);
	$find = array(' ', '&', '\r\n', '\n', '+', '.');
	$url = str_replace ($find, '-', $url);
	$find = array('/[^a-z0-9\-_<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url);
	if($maxsize>0){
		$url = substr($url, 0, $maxsize);
	}
	return $url;
}

function csrfCreate($key, $data=array(), $update=false){
	$token = md5(uniqid(microtime(), true));
	if($update){
		if(isset($_SESSION['csrf_'.$key]) && isset($_SESSION['csrf_'.$key]['token'])){
			$token = $_SESSION['csrf_'.$key]['token'];
		}
	}
	$token_time = time();
	$_SESSION['csrf_'.$key] = array(
		'token'=>$token,
		'time'=>$token_time,
		'data'=>$data
	);
	return $token;
}

function csrfDestroy($key, $token) {
   if(!isset($_SESSION['csrf_'.$key])) {
       return false;
   }
   if ($_SESSION['csrf_'.$key]['token'] !== $token) {
       return false;
   }
   unset($_SESSION['csrf_'.$key]);
}

function csrfVerify($key, $token, $delta_time=0) {
   if(!isset($_SESSION['csrf_'.$key])) {
       return false;
   }
   if ($_SESSION['csrf_'.$key]['token'] !== $token) {
       return false;
   }
   if($delta_time > 0){
       $token_age = time() - $_SESSION['csrf_'.$key]['time'];
       if($token_age >= $delta_time){
		   return false;
       }
   }
   return $_SESSION['csrf_'.$key]['data'];
}

function explodeBirth($birthday){
	$b=array();
	if ($birthday!="0000-00-00" && validateDate($birthday, "Y-m-d")) {
		$t=strtotime($birthday);
		$b=array(
			'dia' => date('j',$t),
			'mes' => date('n',$t),
			'ano' => date('Y',$t),
		);
		return $b;
	}
	return false;
}

function newDeliverNotification($tipo, $id=0, $prioridad=0){
	global $db;
	$stmt = $db->prepare("SELECT did FROM ".DB_PREFIX."deliver_notifications WHERE target_id = ? AND tipo = ? LIMIT 1");
	$stmt->execute(array($id, $tipo));
	$stmt->bindColumn('did', $id);
	if($row = $stmt->Fetch()){
		return $id;
	}else{
		$stmt = $db->prepare("INSERT INTO ".DB_PREFIX."deliver_notifications (target_id, tipo, prioridad, creado) VALUES (?, ?, ?, ?)");
		$stmt->execute(array($id, $tipo, $prioridad, date("Y-m-d H:i:s")));
		$unidad_id = $db->lastInsertId();

		return $unidad_id;
	}
}

function getDeliverNotification($tipo, $id=0){
	global $db;
	$stmt = $db->prepare("SELECT * FROM ".DB_PREFIX."deliver_notifications WHERE target_id = ? AND tipo = ? LIMIT 1");
	$stmt->execute(array($id, $tipo));
	if($row = $stmt->Fetch()){
		$row['data'] = json_decode($row['data']);
		return $row;
	}
	return array();
}

function seems_utf8($str)
{
    $length = strlen($str);
    for ($i=0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) $n = 0; # 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
        elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
        elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
        elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
        elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
        else return false; # Does not match any model
        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}

function remove_accents($string) {
	$string = preg_replace("#[^A-Za-z1-9_.]#","_", $string);
	return $string;
}

function printRedesSociales($datos){
	if(isset($datos['redes'])){
		$redes = json_decode($datos['redes'],true);
		if(!empty($redes)){
			foreach ($redes as $red) {
				if($red[0] == "facebook"){
					$red[0] == "facebook-f";
				}elseif($red[0] == "linkedin"){
					$red[0] == "linkedin-in";
				}
				echo '<a href="'.$red[1].'" class="enlace enlacered '.$red[0].' transition" target="_blank"><i class="fab fa-'.$red[0].'"></i></a>';
			}
		}
	}
}
?>
