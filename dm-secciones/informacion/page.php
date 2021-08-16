<?php
include_once(DMCLASSES.'page.php');
$page = new Page($db);
$page->getPage($_GET["beta"]);
if(!$page->isOpen()){
    include("e404.php");
    exit;
}
$page->addView();
if($page->tipo == 'recurso'){
    if($page->data["has_archivo"]){
        header("Location: ".$page->data["archivo_image"]);
        exit;
    }else{
        header("Location: ".URL);
        exit;
    }

}
$bodys->set('titulo', $page->titulo);
$bodys->setMeta('title',  $page->data["seo_titulo"]);
$bodys->setMeta('description', $page->data["seo_descripcion"]);
if($page->extra["seo_tags"]){
	$bodys->setMeta('keywords', $page->extra["seo_tags"]);
}else{
    $bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
}
$bodys->setMeta('canonical','<link rel="canonical" href="'.$page->url.'">');
$bodys->setMeta('image',  $page->data["cover_image_big"]);
$bodys->set("page", "page");

// Head
require_once( VIEWS .'head.php' );

//Conteido
include_once(DMSECCIONES.$controlador->name.'/formatos/landing.php');

// Footer
require_once( VIEWS .'footer.php' );
?>
