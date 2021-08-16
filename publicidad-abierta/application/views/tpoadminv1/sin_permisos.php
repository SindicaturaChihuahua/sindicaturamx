<?php

/* 
    INAI - SIN PERMISO
 */

?>

<!-- Main content -->
<section class="content">
    <div class="error-page">
        <h2 class="headline text-red"><?php echo $error_number; ?></h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> <?php echo $heading; ?></h3>
            <p>
                <?php echo $error_description; ?>
            </p>
        </div>
    </div><!-- /.error-page -->
</section><!-- /.content -->