		</div> <!-- / c -->
    </div> <!-- / content -->
</div> <!-- / siga -->

<!-- General Use Modal -->
<div class="modal fade" id="modal-general" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
        	Cargando...
        </div>
    </div>
  </div>
</div>

<?php
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
echo '<script type="text/javascript">';
echo 'DMGConfig.fb_appid=\''.FB_AID.'\';';
echo 'DMGConfig.rootpath=\''.BASE.'\';';
echo '</script>';
?>
<script src="<?=URLPLUGINS;?>selectize/selectize.min.js" type="text/javascript"></script>

<!--
Developed by Daniel Muela | www.dmuela.com | muela.daniel@outlook.com
-->

</body>
</html>
