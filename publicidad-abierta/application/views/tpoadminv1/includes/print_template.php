

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15"> 
        <title><?php echo $title; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body onload="window.print()">
        <h3><?php echo $title; ?></h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <?php 
                        for($z = 0; $z < sizeof($nombre_columnas); $z++){
                            echo '<th>' . $nombre_columnas[$z] . '</th>';
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    for($z = 0; $z < sizeof($registros); $z++)
                    {
                        echo '<tr>';
                        for($w = 0; $w < sizeof($registros_columnas); $w++)
                        {
                            if($registros_columnas[$w] == 'id' && !isset($registros[$z][$registros_columnas[$w]])){
                                echo '<td>'. ($z + 1 ) .'</td>';
                            }else if(isset($registros[$z][$registros_columnas[$w]])){
                                echo '<td>'. $registros[$z][$registros_columnas[$w]] .'</td>';
                            }else{
                                echo '<td></td>';
                            }
                            
                        }
                        echo '</tr>';
                    }
                    
                ?>
            </tbody>
        </table>
    </body>
</html>
                    