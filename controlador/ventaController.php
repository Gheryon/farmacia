<?php
include '../modelo/venta.php';
$venta=new Venta();
session_start();
$id_usuario=$_SESSION['usuario'];

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

if($_POST['funcion']=='mostrar_consultas'){
  $venta->venta_dia_vendedor($id_usuario);
  foreach ($venta->objetos as $objeto) {
    $venta_dia_vendedor=$objeto->venta_dia_vendedor;
  }
  $venta->venta_diaria();
  foreach ($venta->objetos as $objeto) {
    $venta_diaria=$objeto->venta_diaria;
  }
  $venta->venta_mensual();
  foreach ($venta->objetos as $objeto) {
    $venta_mensual=$objeto->venta_mensual;
  }
  $venta->venta_anual();
  $json=array();
  foreach ($venta->objetos as $objeto) {
    $json[]=array(
      'venta_dia_vendedor'=>$venta_dia_vendedor,
      'venta_diaria'=>$venta_diaria,
      'venta_mensual'=>$venta_mensual,
      'venta_anual'=>$objeto->venta_anual
    );
  }
  $jsonString=JSON_encode($json[0]);
  echo $jsonString;
}
?>