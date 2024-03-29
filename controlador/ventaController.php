<?php
include_once '../modelo/venta.php';
include_once '../modelo/cliente.php';

$cliente=new Cliente();
$venta=new Venta();
session_start();
$id_usuario=$_SESSION['usuario'];

if($_POST['funcion']=='listar'){
  $venta->buscar();
  $json=array();
  foreach ($venta->objetos as $objeto) {
    //si id_cliente es null, se obtienen los datos de la misma tabla
    if(empty($objeto->id_cliente)){
      $cliente_nombre=$objeto->cliente;
      $cliente_dni=$objeto->dni;
    }else{
      //id_cliente no es null, se hace consulta a la tabla de clientes
      $cliente->buscar_datos_cliente($objeto->id_cliente);
      foreach ($cliente->objetos as $cli) {
        $cliente_nombre=$cli->nombre.' '.$cli->apellidos;
        $cliente_dni=$cli->dni;
      }
    }
    //['data'] es necesario para usar DataTables, es el formato del plugin
    $json['data'][]=array(
      'id_venta'=>$objeto->id_venta,
      'fecha'=>$objeto->fecha,
      'cliente'=>$cliente_nombre,
      'dni'=>$cliente_dni,
      'total'=>$objeto->total,
      'vendedor'=>$objeto->vendedor
    );
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
  $venta->ganancia_mensual();
  $ganancia_mensual='';
  foreach ($venta->objetos as $objeto) {
    $ganancia_mensual=$objeto->ganancia_mensual;
  }
  $venta->venta_anual();
  $json=array();
  foreach ($venta->objetos as $objeto) {
    $json[]=array(
      'venta_dia_vendedor'=>$venta_dia_vendedor,
      'venta_diaria'=>$venta_diaria,
      'venta_mensual'=>$venta_mensual,
      'venta_anual'=>$objeto->venta_anual,
      'ganancia_mensual'=>$venta_mensual-$ganancia_mensual
    );
  }
  $jsonString=JSON_encode($json[0]);
  echo $jsonString;
}
?>