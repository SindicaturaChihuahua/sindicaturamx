<?php
$bodys->set('titulo', "Contacto");
$bodys->setMeta('title', "Contacto");
$bodys->setMeta('description', $opcionesfull["opciones"]["description"]);
$bodys->setMeta('keywords', $opcionesfull["opciones"]["keywords"]);
$bodys->setMeta('canonical','<link rel="canonical" href="'.$controlador->getURL().'">');
$bodys->setMeta('image', URLIMAGES . 'social_share.jpg');
$bodys->set("page", "contacto");
// Head
require_once( VIEWS .'head.php' );
?>
<div class="main">
    <div class="fullwrap spacing-small-top">
        <div class="wrap wrapmed">
            <div class="contacto-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3501.768219851192!2d-106.07850968546762!3d28.636707990643938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86ea434df1aa75cd%3A0x8a06e507f57528e1!2sChihuahua+City+Council!5e0!3m2!1sen!2smx!4v1547073101469" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
            <div class="margin-wrap">
                <div class="contacto-txt">
                    <h2>Visítanos o háblanos</h2>
                    <div class="contacto-txt-wrap">
                        <p><span class="contacto-alt">Dirección</span><br>Ave. Independencia #209, Col. Centro 31000, Chihuahua, Chihuahua</p>
                        <p>marco.loya@mpiochih.gob.mx</p>
                        <p>Whatsapp <i class="fab fa-whatsapp"></i> +52 614 353 6051</p>
                        <p><span class="contacto-alt">Teléfonos</span><br>
                        614 200 4800<br>
                        072 ext. 5458</p>
                    </div>
                </div>
                <div class="contacto-form">
                    <div class="contacto-form-wrap">
                        <h2>Escríbenos</h2>
                        <form class="object-ajax" action="<?=URL;?>api/contacto">
                            <label for="correo">Correo electrónico</label>
                            <input id="correo" type="text" name="correo" placeholder="nombre@correo.com">
                            <label for="mensaje">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" placeholder="Cuéntanos sobre tu denuncia"></textarea>
							<input type="hidden" name="action" value="sendemail">
							<input type="hidden" name="paginareferencia" value="Contacto">
                            <button type="submit" class="btn-primary blockatsubmit">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="contacto-bottom-img">
                <img src="<?=URLIMAGES;?>contacto-sello.png" alt="Sindicatura Chihuahua">
            </div>
        </div>
    </div>
</div>
<?php
// Footer
require_once( VIEWS .'footer.php' );
?>
