<!doctype html>
<html>
<head>

<!-- <script src="jquery.js"></script>
<script src="jquery.sticky.js"></script>
<script>
  $(document).ready(function(){
    $("#sticker").sticky({ topSpacing:50});
  });
</script>
  <style>

    #sticker {
      color: white;
      transform: rotate(-90deg);
      position: absolute;
      left: -21px;
      background: #ff0000;
      width:65px;
      font-family: Droid Sans;
      font-size: 8px;
      line-height: 0.7em;
      text-align: center;
      padding: 4px;
      border-radius: 2px;

    }
  </style> -->


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-136765777-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-136765777-1');
</script>

    <!-- Hotjar Tracking Code for www.sindicatura.mx -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:1249098,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>


    <title><?=$bodys->getTitle();?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width">

    <meta name="google" content="notranslate">
    <link rel="shortcut icon" href="<?=URLIMAGES;?>favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css?family=Barlow:400,600|Montserrat:600,700" rel="stylesheet">
    <link rel="stylesheet" href="<?=URLCSS;?>general<?=ENVIROMENT_SUFFIX;?>.css?v=<?=DMVERSION;?>">

    <?php
	$hasOwlCss = false;
	$hasOwlTheme = false;
    $bodys->printMetaData();
    if ($thiscss = $bodys->get("css")) {
        foreach ($thiscss as $css){
            echo '<link rel="stylesheet" href="'.URL.$css.'">';
        }
    }
    if (isset($controladordata['css']) && !empty($controladordata['css'])) {
        foreach ($controladordata['css'] as $css){
            echo '<link rel="stylesheet" href="'.$css.'">';
			if($css == URLPLUGINS.'owl/assets/owl.carousel.css'){
				$hasOwlCss = true;
			}elseif($css == URLPLUGINS.'owl/assets/owl.theme.default.css'){
				$hasOwlTheme = true;
			}
        }
    }
	if(!$hasOwlCss){
		echo '<link rel="stylesheet" href="'.URLPLUGINS.'owl/assets/owl.carousel.css">';
	}
	if(!$hasOwlTheme){
		echo '<link rel="stylesheet" href="'.URLPLUGINS.'owl/assets/owl.theme.default.css">';
	}
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?=URLJS;?>functions<?=ENVIROMENT_SUFFIX;?>.js?v=<?=DMVERSION;?>" type="text/javascript"></script>
    <script src="<?=URLJS;?>beans<?=ENVIROMENT_SUFFIX;?>.js?v=<?=DMVERSION;?>" type="text/javascript"></script>

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
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
</head>
<body>



<?php
if($bodys->get("normallayout")==true){
?>
<div id="root">
<div id="bodycontent">
    <?php
    if($bodys->get("page") == "page"){
       include_once( VIEWS . 'header_landing.php' );
    }else{
       include_once( VIEWS . 'header.php' );
    }
    ?>
  <?php
}
?>
