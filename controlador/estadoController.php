<?php
include_once '../modelo/estado.php';

$estado = new Estado();

if($_POST['funcion']=='rellenar_estado'){
  $estado->rellenar_estado();
  $json=array();
  foreach ($estado->objetos as $objeto) {
    $json[]=array(
      'id'=>$objeto->id,
      'nombre'=>$objeto->nombre
    );
  }
  $jsonstring=json_encode($json);
  echo $jsonstring;
}

if($_POST['funcion']=='cambiar_estado'){
  $nombre = $_POST['estado'];
  $estado->obtener_id($nombre);
  $json=array();
  foreach ($estado->objetos as $objeto) {
    $json[]=array(
      'id'=>$objeto->id,
      'nombre'=>$objeto->nombre
    );
  }
  $jsonstring=json_encode($json);
  echo $jsonstring;
}
?>
