

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15"> 
        <title><?php echo $title; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.2 -->
        <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <h3><?php echo $title; ?> <span id='load'><i class="fa fa-refresh fa-spin"></i></span></h3>
        
        <a class="btn btn-default hidden" id='export_file' href="#" download="facturas.csv" ><i class="fa fa-file"></i> Descargar a Excel</a>

        
        <script type='text/javascript'>
            var preparar_exportacion = function ()
            {
                
                $.ajax({
                    url: '<?php echo base_url() . 'index.php/tpoadminv1/capturista/facturas/preparar_exportacion_facturas/' ?>',
                    data: {action: 'test'},
                    dataType:'JSON',
                    beforeSend: function () {
                    },
                    complete: function () {
                
                    },
                    error:function () {
                    
                    },
                    success: function (response) {
                        if(response){
                            $('#load').hide();
                            $('#export_file').attr('href', response);
                            $('#export_file').removeClass('hidden');
                            $('#export_file').attr('target', '_blank');
                            setTimeout(function(){ $('#export_file')[0].click() }, 100);
                        }
                    }
                });
            }
        </script>
        <?php echo $scripts; ?>
    </body>

</html>
                    