<?php
if($bodys->get("normallayout")==true){
	include_once(DMCLASSES.'aliados.php');
	include_once(DMCLASSES.'aliado.php');
	$aliados = new Aliados($db);
	$aliados->get();
	$hasOwl = false;
	if($newjs = $bodys->get("bottomjs")) {
	    foreach ($newjs as $njs){
	        if($njs==URLPLUGINS."owl/owl.carousel.js"){
				$hasOwl = true;
			}
		}
	}
	if(!$hasOwl){
		$bodys->set("bottomjs", URLPLUGINS."owl/owl.carousel.js");
	}
?>
	<div class="fullwrap spacing no-bottom">
		<div class="wrap">
			<h2 class="tcenter highlight-blue">Instituciones de transparencia, fiscalización y organizaciones que nos inspiran:</h2>
			<?=$aliados->printSlider();?>
		</div>
	</div>
    <div class="fullwrap spacing-small-top">
        <div class="wrap">
            <div class="margin-wrap">
                <div class="wrap wrapsmall">
                    <div class="margin-wrap">
                        <div class="footer-redes">
                            <div class="footer-redes-inner">
                                <h2 class="highlight-blue">Sigue a la Sindicatura en:</h2>
                                <div class="footer-redes-box">
                                    <a class="footer-circle-redes fcr-facebook-f" href="https://www.facebook.com/SindicaturaChihuahua/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    <a class="footer-circle-redes fcr-twitter" href="https://twitter.com/SindicaturaCUU" target="_blank"><i class="fab fa-twitter"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="footer-redes">
                            <div class="footer-redes-inner">
                                <h2 class="highlight-blue">Sigue al Síndico en:</h2>
                                <div class="footer-redes-box">
                                    <a class="footer-circle-redes fcr-facebook-f" href="https://www.facebook.com/aminanchondo1/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    <a class="footer-circle-redes fcr-instagram" href="https://www.instagram.com/aminanchondo/" target="_blank"><i class="fab fa-instagram"></i></a>
                                    <a class="footer-circle-redes fcr-twitter" href="https://twitter.com/aminanchondo" target="_blank"><i class="fab fa-twitter"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <img class="decor decor02 decor-footer" src="<?=URLIMAGES;?>decor02.png" alt="">
            <img class="decor decor03 decor-footer" src="<?=URLIMAGES;?>decor03.png" alt="">
        </div>
    </div>
    <div class="footer">
        <div class="wrap wrapmed">
            <div class="margin-wrap">
                <div class="footer-box">
                    <img src="<?=URLIMAGES;?>logo-footer.png" alt="Sindicatura Chihuahua">
                         <p>Nuestro mayor compromiso es con la transparencia y  la participación. Si quieres colaborar con nosotros en la vigilancia de los recursos públicos del municipio escríbenos o visítanos.
                         </p>
                         <h5></h5>
                         <h5>Todos los derechos reservados &copy; Sindicatura MX <?=date("Y");?></h5>
                </div>
                <div class="footer-box">
                    <div class="margin-wrap">
                        <div class="footer-box-inner">
                            <h5>Escríbenos o háblanos</h5>
                            <p>Whatsapp <i class="fab fa-whatsapp"></i> +52 614 353 6051</p>
                            <p>+52 1 (614) 200 4800<br>072 ext. 5458</p>

                        </div>
                        <div class="footer-box-inner">
                            <h5>Visítanos</h5>
                            <p>Ave. Independencia #209,<br> Col. Centro 31000, Chihuahua, Chihuahua</p>
                            <p><i class="fa fas fa-envelope"></i>marco.loya@mpiochih.gob.mx</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="footer-copy">
            <div class="wrap">
				<a href="<?=URL;?>política-de-privacidad_1.pdf" class="footer-privacidad" >Políticas de Privacidad: UNO</a>
                <a href="<?=URL;?>política-de-privacidad_2.pdf" class="footer-privacidad"  >Política: DOS</a>
                <a href="<?=URL;?>política-de-privacidad_3.pdf" class="footer-privacidad"  >Política: TRES</a>
            </div>

        </div>
    </div>

</div> <!-- / bodycontent -->
</div>
<?php
}
?>

<?php
require_once( VIEWS . 'beans.php');
if ($thisjs = $bodys->get("bottomjs")) {
    foreach ($thisjs as $js){
        if($js=='pack-fileupload'){
            ?>
            <script src="<?=URLPLUGINS;?>fileupload/js/vendor/jquery.ui.widget.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/tmpl.min.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/load-image.all.min.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/canvas-to-blob.min.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/jquery.iframe-transport.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/jquery.fileupload.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/jquery.fileupload-process.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/jquery.fileupload-image.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/jquery.fileupload-validate.js"></script>
            <script src="<?=URLPLUGINS;?>fileupload/js/jquery.fileupload-ui.js"></script>
            <?php
        }else{
            echo '<script type="text/javascript" src="'.$js.'"></script>';
        }
    }
}
if($bodys->get("page") == "como-vamos"){
    if(isset($indicador)){
        $indicador->printGraficasJS();
    }
}
if($bodys->get("page") == "nosotros" || $bodys->get("page") == "ayuntamiento-miembros" || $bodys->get("page") == "consejo-consultivo"){
    echo '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
}
echo '<script type="text/javascript">';
echo 'DMGConfig.fb_appid=\''.FB_AID.'\';';
echo 'DMGConfig.rootpath=\''.BASE.'\';';
if(logeado()){
    echo 'DMGConfig.loggedin=true;';
}
echo '</script>';

if(strlen($opcionesfull['opciones']['ga_id_seguimiento'])>3 && ENVIROMENT!="dev"){
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?=$opcionesfull['opciones']['ga_id_seguimiento'];?>', 'auto');
  ga('send', 'pageview');

</script>
<?php
}
?>

</body>
</html>
