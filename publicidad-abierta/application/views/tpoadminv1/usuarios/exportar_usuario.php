<?php
require ('includes/config.php');
$BD = new ConnDB(); 

$archivo_csv = fopen('saldos.csv', 'w');

if($archivo_csv)
{
    fputs($archivo_csv, "Código, Nombre, Importe".PHP_EOL));  

    $sql = "SELECT cod, nom, saldo from cuentas where flag = 'X'";
    $sth = $BD->query($sql);
    $sth->execute();

    if ($sth->rowCount() > 0) 
    { 
        while ($fila = $sth->fetch(PDO::FETCH_ASSOC))
        {
           fputs($archivo_csv, implode($fila, ',').PHP_EOL);
        }
    }

    fclose($archivo_csv);
}else{

    echo "El archivo no existe o no se pudo crear";

}