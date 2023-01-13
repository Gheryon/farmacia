<?php
include_once '../modelo/cliente.php';

$cliente = new Cliente();

if($_POST['funcion']=='buscar'){
  $cliente->buscar();
  $json=array();
  date_default_timezone_set('Europe/Madrid');
  $fecha=date('Y-m-d H:i:s');
  $fecha_actual=new DateTime($fecha);
  foreach ($cliente->objetos as $objeto) {
    $nacimiento=new DateTime($objeto->edad);
    $edad=$nacimiento->diff($fecha_actual);
    $json[]=array(
        'id'=>$objeto->id,
        'nombre'=>$objeto->nombre.' '.$objeto->apellidos,
        'dni'=>$objeto->dni,
        'edad'=>$edad->y,
        'telefono'=>$objeto->telefono,
        'correo'=>$objeto->correo,
        'sexo'=>$objeto->sexo,
        'adicional'=>$objeto->adicional,
        'avatar'=>'../img/cliente/'.$objeto->avatar
    );
  }
  $jsonstring=json_encode($json);
  echo $jsonstring;
}

if($_POST['funcion']=='crear'){
  $nombre=$_POST['nombre'];
  $apellidos=$_POST['apellidos'];
  $dni=$_POST['dni'];
  $edad=$_POST['edad'];
  $telefono=$_POST['telefono'];
  $correo=$_POST['correo'];
  $sexo=$_POST['sexo'];
  $adicional=$_POST['adicional'];
  $avatar='default_avatar.png';

  $cliente->crear($nombre, $apellidos, $dni, $edad, $telefono, $correo, $sexo, $adicional, $avatar);
}

if($_POST['funcion']=='editar'){
  $id=$_POST['id'];
  $telefono=$_POST['telefono'];
  $correo=$_POST['correo'];
  $adicional=$_POST['adicional'];

  $cliente->editar($id, $telefono, $correo, $adicional);
}

if($_POST['funcion']=='borrar'){
  $id=$_POST['id'];
  $cliente->borrar($id);
}

if($_POST['funcion']=='rellenar_clientes'){
  $cliente->rellenar_clientes();
  $json=array();
  foreach ($cliente->objetos as $objeto) {
    $json[]=array(
      'id'=>$objeto->id,
      'nombre'=>$objeto->nombre.' '.$objeto->apellidos.' | '.$objeto->dni
    );
  }
  $jsonstring=json_encode($json);
  echo $jsonstring;
}

?>