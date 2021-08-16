<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title><?=$bodys->getTitle();?></title>
    <?php
	$bodys->printMetaData();
    if ($thiscss = $bodys->get("css")) {
        foreach ($thiscss as $css){
            echo '<link rel="stylesheet" href="'.URL.$css.'" />';
        }
    }
    if (isset($controladordata['css']) && !empty($controladordata['css'])) {
        foreach ($controladordata['css'] as $css){
            echo '<link rel="stylesheet" href="'.$css.'" />';
        }
    }
    ?>
    <meta property="fb:app_id" content="<?=FB_AID;?>">
	<meta name="robots" content="noindex, nofollow" />
    <link rel="shortcut icon" href="<?=SIGA;?>images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=SIGA;?>css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=URLPLUGINS;?>toastr/toastr.css" />
    <link rel="stylesheet" href="<?=SIGA;?>css/siga.css" />
    <link rel="stylesheet" href="<?=URLPLUGINS;?>malihuscrollbar/siga.css">
    <link rel="stylesheet" href="<?=URLPLUGINS;?>selectize/selectize.default.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="<?=SIGA;?>js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?=URLPLUGINS;?>toastr/toastr.min.js" type="text/javascript"></script>
    <script src="<?=SIGA;?>js/siga.js" type="text/javascript"></script>
    <?php
    if ($thisjs = $bodys->get("js")) {
        foreach ($thisjs as $js){
            echo '<script type="text/javascript" src="'.URL.$js.'"></script>';
        }
    }
    if (isset($controladordata['js']) && !empty($controladordata['js'])) {
        foreach ($controladordata['js'] as $js){
			echo '<script src="'.$js.'" type="text/javascript"></script>';
        }
    }
    ?>
</head>
<body>

<div id="siga">
	<?php
    require_once( DMSIGA .'header.php' );
	require_once( DMSIGA .'menu.php' );
	?>
	<div id="content">
    	<div id="c">
