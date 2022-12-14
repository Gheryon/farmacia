<?php
include_once '../modelo/proveedor.php';

$proveedor= new Proveedor();

if($_POST['funcion']=='crear'){
    $nombre=$_POST['nombre'];
    $telefono=$_POST['telefono'];
    $correo=$_POST['correo'];
    $direccion=$_POST['direccion'];
    $avatar='prov_default.png';

    $proveedor->crear($nombre, $telefono, $correo, $direccion, $avatar);
}

if($_POST['funcion']=='editar'){
    $id=$_POST['id'];
    $nombre=$_POST['nombre'];
    $telefono=$_POST['telefono'];
    $correo=$_POST['correo'];
    $direccion=$_POST['direccion'];

    $proveedor->editar($id, $nombre, $telefono, $correo, $direccion);
}

if($_POST['funcion']=='buscar'){
    $proveedor->buscar();
    $json=array();
    foreach ($proveedor->objetos as $objeto) {
        $json[]=array(
            'id'=>$objeto->id_proveedor,
            'nombre'=>$objeto->nombre,
            'telefono'=>$objeto->telefono,
            'correo'=>$objeto->correo,
            'direccion'=>$objeto->direccion,
            'avatar'=>'../img/prov/'.$objeto->avatar
        );
    }
    $jsonstring=json_encode($json);
    echo $jsonstring;
}

if($_POST['funcion']=='cambiar_avatar'){
    $id=$_POST['id_logo_prov'];
    $avatar=$_POST['avatar'];
    if(($_FILES['avatar']['type']=='image/jpg')||($_FILES['avatar']['type']=='image/jpeg')||($_FILES['avatar']['type']=='image/png')||($_FILES['avatar']['type']=='image/gif'))
    {
        $nombre=uniqid().'-'.$_FILES['avatar']['name'];
        $ruta='../img/prov/'.$nombre;
        move_uploaded_file($_FILES['avatar']['tmp_name'],$ruta);
        $proveedor->cambiar_logo($id, $nombre);
        //hay que evitar borrar el prov_default.png, se borran solo si se llaman distinto a este
        if($avatar!='../img/prov/prov_default.png'){
            unlink($avatar);
        }
        $json=array();
        $json[]=array(
            'ruta'=>$ruta,
            'alert'=>'edit'
        );
        $jsonstring=json_encode($json[0]);
        echo $jsonstring;
    }else{
        $json=array();
        $json[]=array(
            'alert'=>'noedit'
        );
        $jsonstring=json_encode($json[0]);
        echo $jsonstring;
    }
}

if($_POST['funcion']=='borrar'){
    $id=$_POST['id'];
    $proveedor->borrar($id);
}

if($_POST['funcion']=='rellenar_proveedores'){
    $proveedor->rellenar_proveedores();
    $json = array();
    foreach ($proveedor->objetos as $objeto) {
        $json[]=array(
            'id'=>$objeto->id_proveedor,
            'nombre'=>$objeto->nombre
        );
    }
    $jsonstring=json_encode($json);
    echo $jsonstring;
}
?>