<div id="menu">
	<div class="menucon">
        <div class="tabscontainer">
        	<div class="tcon">
				<?php
                $url->printMenu($user);
                ?>
        	</div>
        </div>
    </div>
    <div class="bottommenu">
    	<div class="user cfix">
        	<a href="<?=SIGA;?>p/micuenta/logout" class="opt cerrarsesion bstooltip" title="Cerrar Sesión" data-toggle="tooltip" data-placement="top"><i class="fa fa-power-off"></i></a>
            <a href="<?=SIGA;?>p/micuenta" class="opt useraccount"><img src="<?=$user->getProfilePic();?>" class="user-image" /> <?=$user->pseudonimo;?></a>
        </div>
    </div>
</div>