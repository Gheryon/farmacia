<?php
include_once '../modelo/venta.php';
include_once '../modelo/conexion.php';

$venta = new Venta();
session_start();
$vendedor=$_SESSION['usuario'];

if($_POST['funcion']=='registrar_compra'){
	$total=$_POST['total'];
	$cliente=$_POST['cliente'];
	$productos=json_decode($_POST['json']);
	date_default_timezone_set('Europe/Madrid');
	$fecha=date('Y-m-d H:i:s');
	$venta->crear($cliente, $total, $fecha, $vendedor);
	$venta->ultima_venta();
	foreach ($venta->objetos as $objeto) {
		$id_venta=$objeto->ultima_venta;
	}
	try {
		$db=new Conexion();
		$conexion=$db->pdo;
		$conexion->beginTransaction();
		foreach ($productos as $prod) {
			$cantidad=$prod->cantidad;
			while ($cantidad!=0) {
				//en el paréntesis, de todos los lotes de un prod_id_prod, coge el que tenga la fecha más próxima a vencerse, devuelve la fecha.
				//después selecciona los datos de la tabla del lote con ese vencimiento
				$sql="SELECT * FROM lote WHERE vencimiento = (SELECT MIN(vencimiento) FROM lote WHERE id_producto=:id AND estado='A') and id_producto=:id";
				$query=$conexion->prepare($sql);
				$query->execute(array(':id'=>$prod->id));
				$lote=$query->fetchAll();
				//va recorriendo los lotes desde el más próximo al vencimiento y va restando a la cantidad
				foreach ($lote as $lote) {
					$sql="SELECT compra.id_proveedor as proveedor FROM lote
					JOIN compra ON lote.id_compra=compra.id AND lote.id=:id";
					$query=$conexion->prepare($sql);
					$query->execute(array(':id'=>$lote->id));
					$prov=$query->fetchAll();
					//solo se trajo un elemento en $prov, por eso se accede al [0]
					$proveedor=$prov[0]->proveedor;
					//cantidad se vuelve 0, pues este lote provee de todos los suministros deseados. La variable cantidad es la que el usuario quiere comprar, no la que hay en stock
					if($cantidad<$lote->cantidad_lote){
						//este sql introduce cada vez que se produce una venta a la tabla detalle_venta la información de la venta a modo de historial
						$sql="INSERT INTO detalle_venta(det_cantidad, det_vencimiento, id__det_lote, id__det_prod, lote_id_prov, id_det_venta) VALUES('$cantidad', '$lote->vencimiento', '$lote->id', '$prod->id', '$proveedor', '$id_venta')";
						$conexion->exec($sql);
						$conexion->exec("UPDATE lote SET cantidad_lote= cantidad_lote-'$cantidad' WHERE id ='$lote->id'");
						$cantidad=0;
					}
					//se consume todo el stock, se borra el lote
					if($cantidad==$lote->cantidad_lote){
						$sql="INSERT INTO detalle_venta(det_cantidad, det_vencimiento, id__det_lote, id__det_prod, lote_id_prov, id_det_venta) VALUES('$cantidad', '$lote->vencimiento', '$lote->id', '$prod->id', '$proveedor', '$id_venta')";
						$conexion->exec($sql);
						$conexion->exec("UPDATE lote SET estado='I', cantidad_lote=0 WHERE id ='$lote->id'");
						$cantidad=0;
					}
					//la cantidad es superior al stock, por lo tanto todo el lote se consume entero, por tanto se borra
					if($cantidad>$lote->cantidad_lote){
						$sql="INSERT INTO detalle_venta(det_cantidad, det_vencimiento, id__det_lote, id__det_prod, lote_id_prov, id_det_venta) VALUES('$lote->cantidad_lote', '$lote->vencimiento', '$lote->id', '$prod->id', '$proveedor', '$id_venta')";
						$conexion->exec($sql);
						$conexion->exec("UPDATE lote SET estado='I', cantidad_lote=0 WHERE id ='$lote->id");
						$cantidad=$cantidad-$lote->cantidad_lote;
					}
				}
			}
			$subtotal=$prod->cantidad*$prod->precio;
			$conexion->exec("INSERT INTO venta_producto(precio, cantidad, subtotal, producto_id_producto, venta_id_venta) VALUES ('$prod->precio','$prod->cantidad', '$subtotal', '$prod->id', '$id_venta')");
		}
		$conexion->commit();
	} catch (Exception $error) {
		//rollBack anula todo lo del try si algo saliese mal
		$conexion->rollBack();
		$venta->borrar($id_venta);
		echo $error->getMessage();
	}
}
?>
