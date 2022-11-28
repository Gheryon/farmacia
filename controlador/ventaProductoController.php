<?php
include_once '../modelo/ventaProducto.php';

$venta_producto = new VentaProducto();

if($_POST['funcion']=='ver_venta'){
  $id=$_POST['id'];
  $venta_producto->buscar($id);
  $json=array();
  foreach ($venta_producto->objetos as $objeto) {
    $json[]=$objeto;
  }
  $jsonString=JSON_encode($json);
  echo $jsonString;
}
?>