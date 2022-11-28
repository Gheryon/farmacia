<?php
include '../modelo/venta.php';
$venta=new Venta();

if($_POST['funcion']=='listar'){
    $venta->buscar();
    $json=array();
    foreach ($venta->objetos as $objeto) {
      //['data'] es necesario para usar DataTables, es el formato del plugin
      $json['data'][]=$objeto;
    }
    $jsonString=JSON_encode($json);
    echo $jsonString;
}

?>