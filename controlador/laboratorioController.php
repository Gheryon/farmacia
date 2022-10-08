<?php
include '../modelo/laboratorio.php';

$laboratorio=new Laboratorio();

if($_POST['funcion']=='crear')
{
    $nombre = $_POST['nombre_laboratorio'];
    $avatar = 'lab_default.png';
    $laboratorio->crear($nombre, $avatar);
}

if($_POST['funcion']=='editar')
{
    $nombre = $_POST['nombre_laboratorio'];
    $id_editado = $_POST['id_editado'];
    $laboratorio->editar($nombre, $id_editado);
}

if($_POST['funcion']=='buscar')
{
    $laboratorio->buscar();
    $json=array();
    foreach ($laboratorio->objetos as $objeto) {
        $json[]=array(
            'id'=>$objeto->id_laboratorio,
            'nombre'=>$objeto->nombre,
            'avatar'=>'../img/lab/'.$objeto->avatar
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

if($_POST['funcion']=='cambiar_logo'){
    $id=$_POST['id_logo_lab'];
    if(($_FILES['avatar']['type']=='image/jpg')||($_FILES['avatar']['type']=='image/jpeg')||($_FILES['avatar']['type']=='image/png')||($_FILES['avatar']['type']=='image/gif'))
    {
        $nombre=uniqid().'-'.$_FILES['avatar']['name'];
        $ruta='../img/lab/'.$nombre;
        move_uploaded_file($_FILES['avatar']['tmp_name'],$ruta);
        $laboratorio->cambiar_logo($id, $nombre);
        //hay que evitar borrar el lab_default.png, se borran solo si se llaman distinto a este
        foreach ($laboratorio->objetos as $objeto) {
            if($objeto->avatar!='lab_default.png'){
                unlink('../img/lab/'.$objeto->avatar);
            }
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
    $laboratorio->borrar($id);
}

if($_POST['funcion']=='rellenar_laboratorios'){
    $laboratorio->rellenar_laboratorios();
    $json = array();
    foreach ($laboratorio->objetos as $objeto) {
        $json[]=array(
            'id'=>$objeto->id_laboratorio,
            'nombre'=>$objeto->nombre
        );
    }
    $jsonstring=json_encode($json);
    echo $jsonstring;
}
?>