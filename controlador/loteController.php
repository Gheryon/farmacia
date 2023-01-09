<?php
include '../modelo/lote.php';
$lote=new Lote();

if($_POST['funcion']=='crear-lote'){
    $id_producto=$_POST['id_producto'];
    $proveedor=$_POST['proveedor'];
    $stock=$_POST['stock'];
    $vencimiento=$_POST['vencimiento'];
    $lote->crearLote($id_producto, $proveedor, $stock, $vencimiento);
}

if($_POST['funcion']=='editar-lote'){
    $id=$_POST['id'];
    $stock=$_POST['stock'];
    $lote->editarLote($id, $stock);
}

if($_POST['funcion']=='borrar'){
    $id=$_POST['id'];
    $lote->borrarLote($id);
}

if($_POST['funcion']=='buscar-lote'){
    $lote->buscar();
    $json=array();
    date_default_timezone_set('Europe/Madrid');
    $fecha=date('Y-m-d H:i:s');
    $fecha_actual = new DateTime($fecha);
    foreach ($lote->objetos as $objeto) {
        $vencimiento = new DateTime($objeto->vencimiento);
        $diferencia=$vencimiento->diff($fecha_actual);
        $mes=$diferencia->m;
        $dia=$diferencia->d;
        $verificado=$diferencia->invert;
        //verificado=0 cuando la diferencia entre dos fechas es negativa o es 0, con lo que la fecha estaría vencida
        if($verificado==0){
            $estado='danger';
            $mes=$mes*(-1);
            $dia=$dia*(-1);
        }else{
            if($mes>3){
                $estado='light';
            }
            if($mes<=3){
                $estado='warning';
            }
        }
        $json[]=array(
            'id'=>$objeto->id_lote,
            'nombre'=>$objeto->prod_nom,
            'concentracion'=>$objeto->concentracion,
            'adicional'=>$objeto->adicional,
            'vencimiento'=>$objeto->vencimiento,
            'proveedor'=>$objeto->prov_nom,
            'stock'=>$objeto->stock,
            'laboratorio'=>$objeto->lab_nom,
            'tipo'=>$objeto->tip_nom,
            'presentacion'=>$objeto->pre_nom,
            'avatar'=>'../img/prod/'.$objeto->logo,
            'mes'=>$mes,
            'dia'=>$dia,
            'estado'=>$estado
        );
    }
    $jsonstring=json_encode($json);
    echo $jsonstring;
}

?>