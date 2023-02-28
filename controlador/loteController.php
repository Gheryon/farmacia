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



///////////////actualización//////////////////////
if($_POST['funcion']=='ver_compra'){
	$id=$_POST['id'];
	$lote->ver($id);
	$contador=0;
	$json=array();
  foreach ($lote->objetos as $objeto) {
    $contador++;
    $json[]=array(
      'numero'=>$contador,
      'codigo'=>$objeto->codigo,
      'cantidad'=>$objeto->cantidad,
      'vencimiento'=>$objeto->vencimiento,
      'precio_compra'=>$objeto->precio_compra,
      'producto'=>$objeto->producto.' | '.$objeto->concentracion.' | '.$objeto->adicional,
      'laboratorio'=>$objeto->laboratorio,
      'presentacion'=>$objeto->presentacion,
      'tipo'=>$objeto->tipo
    );
  }
  $jsonstring=json_encode($json);
  echo $jsonstring;
}

if($_POST['funcion']=='buscar_lotes_riesgo'){
	$lote->buscar();
	$json=array();
	date_default_timezone_set('Europe/Madrid');
	$fecha=date('Y-m-d H:i:s');
	$fecha_actual = new DateTime($fecha);
	foreach ($lote->objetos as $objeto) {
		$vencimiento = new DateTime($objeto->vencimiento);
		$diferencia=$vencimiento->diff($fecha_actual);
		$anno=$diferencia->y;
		$mes=$diferencia->m;
		$dia=$diferencia->d;
		$hora=$diferencia->h;
		$verificado=$diferencia->invert;
		//verificado=0 cuando la diferencia entre dos fechas es negativa o es 0, con lo que la fecha estaría vencida
		$estado='light';
		if($verificado==0){
			$estado='danger';
			$anno=$anno*(-1);
			$mes=$mes*(-1);
			$dia=$dia*(-1);
			$hora=$hora*(-1);
		}else{
			if($mes>3){
				$estado='light';
			}
			if($mes<=3&&$anno==0){
				$estado='warning';
			}
		}
		if($estado=='danger'||$estado=='warning'){
			$json[]=array(
				'id'=>$objeto->id_lote,
				'nombre'=>$objeto->prod_nom,
				'concentracion'=>$objeto->concentracion,
				'adicional'=>$objeto->adicional,
				'vencimiento'=>$objeto->vencimiento,
				'proveedor'=>$objeto->prov_nom,
				'stock'=>$objeto->cantidad_lote,
				'laboratorio'=>$objeto->lab_nom,
				'tipo'=>$objeto->tip_nom,
				'presentacion'=>$objeto->pre_nom,
				'avatar'=>'../img/prod/'.$objeto->logo,
				'anno'=>$anno,
				'mes'=>$mes,
				'dia'=>$dia,
				'hora'=>$hora,
				'estado'=>$estado
			);
		}
	}
	$jsonstring=json_encode($json);
	echo $jsonstring;
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
		$anno=$diferencia->y;
		$mes=$diferencia->m;
		$dia=$diferencia->d;
		$hora=$diferencia->h;
		$verificado=$diferencia->invert;
		//verificado=0 cuando la diferencia entre dos fechas es negativa o es 0, con lo que la fecha estaría vencida
		$estado='light';
		if($verificado==0){
			$estado='danger';
			$anno=$anno*(-1);
			$mes=$mes*(-1);
			$dia=$dia*(-1);
			$hora=$hora*(-1);
		}else{
			if($mes>3){
				$estado='light';
			}
			if($mes<=3&&$anno==0){
				$estado='warning';
			}
		}
		$json[]=array(
			'id'=>$objeto->id_lote,
			'codigo'=>$objeto->codigo,
			'nombre'=>$objeto->prod_nom,
			'concentracion'=>$objeto->concentracion,
			'adicional'=>$objeto->adicional,
			'vencimiento'=>$objeto->vencimiento,
			'proveedor'=>$objeto->prov_nom,
			'stock'=>$objeto->cantidad_lote,
			'laboratorio'=>$objeto->lab_nom,
			'tipo'=>$objeto->tip_nom,
			'presentacion'=>$objeto->pre_nom,
			'avatar'=>'../img/prod/'.$objeto->logo,
			'anno'=>$anno,
			'mes'=>$mes,
			'dia'=>$dia,
			'hora'=>$hora,
			'estado'=>$estado
		);
	}
	$jsonstring=json_encode($json);
	echo $jsonstring;
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

?>