<?php
if(logeado() && $user->exists && $user->hasRole($controlador->permiso)){

	$cHeaderData=array(
		'titulo' => 'Configuración',
		'icono' => 'cog',
		'opciones' => array()
	);
	$bodys->set( 'bottomjs', URLPLUGINS.'jqueryui/jquery-ui.min.js' );
	$bodys->set( 'bottomjs', SIGA.'pages/'.$controlador->name.'/f.js' );

    $opciones = getOpciones();

	include( DMSIGA .'breadcrumbs.php' );
	include( DMSIGA .'cHeader.php' );

	//Cargar clase de seccion
	echo '<div class="boxcon"><div class="box">';
		?>
        <form role="form" method="post" action="<?=$url->createUrl($controlador->static_urls['nuevo-handler']);?>" class="object-ajax">
        <input type="hidden" name="manage" value="manage" />
        <input type="hidden" name="action" value="editar" />
        <div class="row">
            <div class="col-sm-12">
				<div class="row">
                    <div class="col-sm-3">
                        <h4>Configuración General</h4>
                        <p>Edita la información general de tu sitio. El nombre de tu sitio se muestra en los buscadores, al igual que la descripción y las palabras clave, mientras que la información de contacto solo se muestra en algunas secciones específicas.</p>
                    </div>
                    <div class="col-sm-9">
                    	<div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Título de la página</label>
                                    <input type="text" class="form-control" name="site_title" value="<?=$opciones['site_title'];?>">
                                </div>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Descripción</label>
                                    <input type="text" class="form-control" name="description" value="<?=$opciones['description'];?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Palabras clave</label>
                                    <input type="text" class="form-control" name="keywords" value="<?=$opciones['keywords'];?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Nombre de contacto</label>
                                    <input type="text" class="form-control" name="contacto_nombre" value="<?=$opciones['contacto_nombre'];?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Email de contacto</label>
                                    <input type="text" class="form-control" name="contacto_email" value="<?=$opciones['contacto_email'];?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

				<!-- Redes -->
				<div class="row" id="redes" data-actionurl="<?=$url->createUrl($controlador->static_urls['nuevo-handler']);?>?action=red">
					<div class="col-sm-3">
						<h4>Redes Sociales</h4>
                        <p>Agrega los enlaces a tus redes sociales.</p>
						<a href="#" class="btn btn-default btn-sm addaction"><span class="fa fa-plus"></span> &nbsp;Añadir red</a>
					</div>
					<div id="redescon" class="col-sm-9 sortable">
						<?php
						$redes = json_decode($opciones['redes'], true);
						if(!empty($redes)){
							foreach($redes as $r){
								$controlador->templateRedes($r);
							}
						}
						?>
					</div>
				</div>

                <hr />
                <div class="row">
                    <div class="col-sm-3">
                        <h4>Google Analytics</h4>
                        <p>Ingresa tu ID de seguimiento de Google Analytics para comenzar a registrar las visitas, páginas vistas y más de tu sitio web.</p>
                        <p>El seguimiento se realizara en todas las páginas <b>públicas</b> de tu sitio.</p>
                    </div>
                    <div class="col-sm-9">
                    	<div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">ID de seguimiento de Google Analytics</label>
                                    <input type="text" class="form-control" name="ga_id_seguimiento" value="<?=$opciones['ga_id_seguimiento'];?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-3">
                        <h4>Sistema de comentarios</h4>
                        <p>Ingresa tu ID de cuenta Disqus.</p>
                        <p>Disqus es el sistema de comentarios más utilizado en el mundo, te proporcionara la mayor seguridad y fiabilidad.</p>
                    </div>
                    <div class="col-sm-9">
                    	<div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">ID de cuenta Disqus</label>
                                    <input type="text" class="form-control" name="disqus_id" value="<?=$opciones['disqus_id'];?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary blockatsubmit" name="save">Guardar</button> <img src="<?=SIGA;?>images/loading.gif" class="floading" />
                        </div>
                    </div>
                </div><!-- end big row -->
            </div>
        </div>
        </form>
		<?php
	echo '</div></div>';
?>

<?php
}else{
	include('noaccess.php');
}
?>
