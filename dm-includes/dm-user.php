<?php
class User
{
    private $privileges;
	protected $db;
	public $exists=false;
	public $details;
	public $actions;

    public function __construct($db) {
		$this->db = $db;
    }

    private function setUserData($user){
    	$this->exists = true;
		$this->uid = $user["uid"];
		$this->username = $user["username"];
		$this->email = $user["email"];
		$this->password = $user["password"];
		$this->nombre = $user["nombre"];
		$this->apellidopaterno = $user["apellidopaterno"];
		$this->apellidomaterno = $user["apellidomaterno"];
		$this->pseudonimo = $user["pseudonimo"];
		$this->cover = $user["cover"];
		$this->status = $user["status"];
		$this->nivel_acc = $user["nivel_acc"];
		$this->hashcode = $user["hashcode"];
		$this->perms = $user["perms"];
		$this->registrado = $user["registrado"];
		$this->initPrivileges();
    }

    // Get user by user_id
    public function getByUid($uid) {
		if($stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users WHERE uid = ? LIMIT 1")){
			$stmt->execute(array($uid));
			if($row = $stmt->Fetch()){
				$this->setUserData($row);
				return true;
			}
		}
		return false;
    }

    // Get user by username
    public function getByUsername($username) {
    	if($stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users WHERE username = ? LIMIT 1")){
			$stmt->execute(array($username));
			if($row = $stmt->Fetch()){
				$this->setUserData($row);
				return true;
			}
		}
		return false;
    }

    // Get user by email
    public function getByEmail($email) {
    	if($stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users WHERE email = ? LIMIT 1")){
			$stmt->execute(array($email));
			if($row = $stmt->Fetch()){
				$this->setUserData($row);
				return true;
			}
		}
		return false;
    }

	// Validate user
    public function validateUser($user, $pass) {
    	$sql="SELECT uid FROM ".DB_PREFIX."users WHERE ";
    	if(validaMail($user)){
    		$sql.="email = ?";
    	}else{
    		$sql.="username = ?";
    	}
    	$sql.=" AND password = BINARY ? LIMIT 1";

		if($stmt = $this->db->prepare($sql)){
			$stmt->execute(array($user,$pass));
			$stmt->BindColumn('uid',$uid);
			if($stmt->Fetch()){
				if($this->getByUid($uid)){
					return true;
				}
			}
		}
		return false;
    }

    // Validate user using the Facebook user_id
    public function validateUserFacebook($fbid) {
		if($stmt = $this->db->prepare("SELECT t1.uid as uid FROM ".DB_PREFIX."users_connect as t1 JOIN ".DB_PREFIX."users as t2 ON t1.uid=t2.uid WHERE t1.account_uid = ? AND t1.tipo = ? LIMIT 1")){
			$stmt->execute(array($fbid, 'facebook'));
			$stmt->BindColumn('uid',$uid);
			if($stmt->Fetch()){
				if($this->getByUid($uid)){
					return true;
				}
			}
		}
		return false;
    }

    // Validate user using the Twitter user_id
    public function validateUserTwitter($id) {
		if($stmt = $this->db->prepare("SELECT t1.uid as uid FROM ".DB_PREFIX."users_connect as t1 JOIN ".DB_PREFIX."users as t2 ON t1.uid=t2.uid WHERE t1.account_uid = ? AND t1.tipo = ? LIMIT 1")){
			$stmt->execute(array($id, 'twitter'));
			$stmt->BindColumn('uid',$uid);
			if($stmt->Fetch()){
				if($this->getByUid($uid)){
					return true;
				}
			}
		}
		return false;
    }

	public function tryLoginFromOs(){
		if(isset($_REQUEST['accesstoken']) && isset($_REQUEST['os']) && isset($_REQUEST['deviceid']) && isset($_REQUEST['privatekey'])){
			if(($_REQUEST['os']=='android' && $_REQUEST['privatekey']==DM_ANDROID_PRIVATE) || ($_REQUEST['os']=='ios' && $_REQUEST['privatekey']==DM_IOS_PRIVATE)){
				if($stmt = $this->db->prepare("SELECT uid, conectada, last_refresh FROM ".DB_PREFIX."users_device_connect
					WHERE deviceid = ? AND tipo = ? AND token = ? AND conectada = ?
					LIMIT 1")){
					$stmt->execute(array($_REQUEST['deviceid'], $_REQUEST['os'], $_REQUEST['accesstoken'], 1));
					if($row = $stmt->Fetch()){
						if($this->getByUid($row['uid'])){
							$this->login();
							return true;
						}
					}
				}
			}
		}
		return false;
	}

	//Trata de recordar usuario
	public function trylogin(){
		$info_cookie=$this->get_Cookies();
		if($info_cookie){
			if($this->validateUserCookie($info_cookie[0],$info_cookie[1])){
				$this->login();
				$this->createCookies();
			}
		}
	}

	// Validate user from cookie
    public function validateUserCookie($uid, $hashcode) {
		if($stmt = $this->db->prepare("SELECT hashid, uid FROM ".DB_PREFIX."users_hashcodes WHERE uid = BINARY ? AND tipo = ? AND token = BINARY ? LIMIT 1")){
			$stmt->execute(array($uid, 'cookie', $hashcode));
			$stmt->BindColumn('uid',$uid);
			$stmt->BindColumn('hashid',$hashid);
			if($stmt->Fetch()){
				$stmt = $this->db->prepare("DELETE FROM ".DB_PREFIX."users_hashcodes WHERE hashid = ? LIMIT 1");
    			$stmt->execute(array($hashid));
				if($this->getByUid($uid)){
					return true;
				}
			}
		}
		return false;
    }

	public function createCookies(){
		$segundos = 604800;
		$fecha = date("Y-m-d H:i:s");
		$fechaexpira = time() + $segundos;
		$fechaexpira = date("Y-m-d H:i:s", $fechaexpira);
		if ($this->exists) {
			$newhash=crearCadena(30);
			$string=$this->uid.'||'.$newhash;
			$string=encrypt($string,DM_HASH_KEY);
			setcookie('dmci', $string, time()+$segundos, '/');
			if($stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX."users_hashcodes (uid, tipo, token, creado, expira) VALUES (?, ?, ?, ?, ?)")){
				$stmt->execute(array($this->uid, 'cookie', $newhash, $fecha, $fechaexpira));
			}
		}
	}

	public function get_Cookies(){
		if(isset($_COOKIE['dmci'])){
			$args = array();
			$string=decrypt($_COOKIE['dmci'],DM_HASH_KEY);
			$args = explode('||',trim($string));
			deleteCookies();
			return $args;
		}else{
			return false;
		}
	}

    // populate roles with their associated permissions
    protected function initPrivileges() {
        $this->privileges = array();

		if(isset($this->perms) && strlen($this->perms)>0 && $this->status>0){
			$perms=explode("##",$this->perms);
			if(count($perms)){
				foreach($perms as $perm){
					if($perm!=""){
						$this->privileges[$perm] = true;
					}
				}
			}
		}
    }

	// check if a user has a specific role
	public function hasPrivilege($role_name) {
		if ($this->exists) {
			if($this->isFolklore()){
				return true;
			}
			return isset($this->privileges[$role_name]);
		}
		return false;
	}

	// check if a user has a specific role
	public function hasRole($role_name) {
		if ($this->exists) {
			if($this->isFolklore()){
				return true;
			}
			return isset($this->privileges[$role_name]);
		}
		return false;
	}

	public function isFolklore(){
		if ($this->exists && $this->nivel_acc==100) {
			return true;
		}
		return false;
	}

	public function isStaff(){
		if ($this->exists && $this->nivel_acc>=51) {
			return true;
		}
		return false;
	}

	public function isPremium(){
		if ($this->exists && $this->premium==1) {
			return true;
		}
		return false;
	}

	public function getAllPrivileges() {
		return $this->privileges;
	}

    // Log In this specific user
    public function login() {
        if ($this->exists) {
			$_SESSION['uid'] = $this->uid;
			$_SESSION['logeado'] = true;
			$this->sesionRecord();
			return true;
        } else {
            return false;
        }
    }

    // Sesiones de acceso
    public function sesionRecord() {
        if ($this->exists) {
			$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX."users_sesiones (uid, ip, fecha) VALUES (?, ?, ?)");
			$stmt->execute(array($this->uid, get_client_ip(), date("Y-m-d H:i:s")));
        }
    }

    // Get Profile picture
    public function getProfilePic($size="medium") {
        if (strlen($this->cover)>4) {
			return URLPAGES."usuarios/obj".$this->uid."/".$size."_".$this->cover;
        } else {
            return URLIMAGES."profilepicture/medium_default.jpg";
        }
    }

    // Get Empresa Cover
    public function getCover($size="big") {
        if (strlen($this->details['empresacover'])>4) {
			return URLPAGES."usuarios/obj".$this->uid."/".$size."_".$this->details['empresacover'];
        } else {
            return URLIMAGES."default_cover_empresa.jpg";
        }
    }

	public function newUser(){
		$stmt = $this->db->prepare("SELECT uid FROM ".DB_PREFIX."users WHERE status = 3 LIMIT 1");
		$stmt->execute();
		$stmt->BindColumn('uid',$uid);
		if($row = $stmt->Fetch()){
			return $uid;
		}else{
			$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX."users (username, email, nivel_acc, status) VALUES ('','',0,3)");
			$stmt->execute();
			return $this->db->lastInsertId();
		}
		return false;
	}

    public function getDetails() {
		if ($this->exists && empty($this->details)) {
			$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users_info WHERE uid = ? LIMIT 1");
	 		$stmt->execute(array($this->uid));

			if ($row = $stmt->Fetch()) {
				$this->details = $row;
				return true;
			} else {
				$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX."users_info (uid) VALUES (?)");
				$stmt->execute(array($this->uid));
				$this->getDetails();
				return true;
			}
		}
    }

    public function getActions() {
		if ($this->exists && empty($this->actions)) {
			$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users_actions WHERE uid = ? LIMIT 1");
	 		$stmt->execute(array($this->uid));

			if ($row = $stmt->Fetch()) {
				$this->actions = $row;
				return true;
			} else {
				$stmt = $this->db->prepare("INSERT INTO ".DB_PREFIX."users_actions (uid) VALUES (?)");
				$stmt->execute(array($this->uid));
				$this->getActions();
				return true;
			}
		}
    }

	public function getConnectAccount($id, $tipo='facebook') {
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users_connect WHERE uid = ? AND tipo = ? LIMIT 1");
		$stmt->execute(array($id, $tipo));

		if ($row = $stmt->Fetch()) {
			return $row;
		} else {
		    return array();
		}
	}

	public function connectAccountExists($id, $tipo='facebook') {
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users_connect WHERE account_uid = ? AND tipo = ? LIMIT 1");
		$stmt->execute(array($id, $tipo));

		if ($row = $stmt->Fetch()) {
			return true;
		} else {
		    return false;
		}
	}

	public function deviceAccountExists($deviceid, $os='android') {
		$stmt = $this->db->prepare("SELECT * FROM ".DB_PREFIX."users_device_connect WHERE deviceid = ? AND tipo = ? LIMIT 1");
		$stmt->execute(array($deviceid, $os));

		if ($row = $stmt->Fetch()) {
			return true;
		} else {
		    return false;
		}
	}

}
?>
