<?php
/*
 * Copyright 2014, Daniel Muela
 * http://www.dmuela.com
 *
 */

define('DMINCLUDES', dirname(__FILE__) . '/' );
define('DMSIGA', DMINCLUDES . '../siga/' );

require_once(DMINCLUDES . 'dm-initial.php');

define('ROOT', DMINCLUDES.'../');
define('SIGA', URL.'siga/');
define('THEME', DMINCLUDES . '../themes/' . FK_THEME . '/');
define('THEME_SECCIONES', THEME . 'secciones/');
define('THEME_MODULOS', THEME . 'modulos/');
define('PLUGINS', DMINCLUDES . '../public/plugins/');
define('DMSECCIONES', ROOT . 'dm-secciones/');
define('DMCLASSES', ROOT . 'dm-classes/');
define('TIMEDIF', 0);

define('URLIMAGES', URL.'public/images/');
define('URLIMG', URL.'public/img/');
define('URLCARGAS', URL.'public/cargas/');
define('URLASSETS', URL.'public/assets/');
define('URLJS', URL.'public/js/');
define('URLCSS', URL.'public/css/');
define('URLPAGES', URL.'public/pages/');
define('URLPLUGINS', URL.'public/plugins/');
define('URLOBJECTS', URL.'public/objects/');
define('URLTHEME', URL.'themes/'.FK_THEME.'/');
define('CACHE', ROOT.'dm-cache/');

define('PROFILES', DMINCLUDES.'../public/profiles/');
define('VIEWS', DMINCLUDES.'../dm-views/');

define('MEGABYTE', 1048576);

if (!session_id()) {
	session_start();
}

require_once(DMINCLUDES . 'dm-settings.php');
?>
