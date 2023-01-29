<?php
include_once '../modelo/compras.php';
include_once '../modelo/lote.php';

$compras = new Compras();
$lote = new Lote();

if($_POST['funcion']=='registrar_compra'){
  $descripcion=json_decode($_POST['descripcionString']);
  $productos=json_decode($_POST['productosString']);
  $compras->crear($descripcion->codigo, $descripcion->fecha_compra, $descripcion->fecha_entrega, $descripcion->total, $descripcion->estado, $descripcion->proveedor);
  $compras->ultima_compra();
  foreach ($compras->objetos as $objeto) {
    $id_compra=$objeto->ultima_compra;
  }
  foreach ($productos as $prods) {
    $lote->crear_lote($prods->codigo, $prods->cantidad, $prods->vencimiento, $prods->precio_compra, $id_compra, $prods->id);
  }
}

if($_POST['funcion']=='mostrar_compras'){
  $compras->mostrar_compras();
  $contador=0;
  foreach ($compras->objetos as $objeto) {
    $contador++;
    $json[]=array(
      'numero'=>$contador,
      'codigo'=>$objeto->codigo,
      'fecha_compra'=>$objeto->fecha_compra,
      'fecha_entrega'=>$objeto->fecha_entrega,
      'total'=>$objeto->total,
      'estado'=>$objeto->estado,
      'proveedor'=>$objeto->proveedor
    );
  }
  $jsonstring=json_encode($json);
  echo $jsonstring;
}

if($_POST['funcion']=='cambiar_estado'){
  $id_compra=$_POST['id_compra'];
  $id_estado=$_POST['id_estado'];
  $compras->cambiar_estado($id_estado, $id_compra);
  echo 'edit';
}
?>