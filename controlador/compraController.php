<?php
include_once '../modelo/venta.php';
//conexion.php para usar pdo y transacciones
include_once '../modelo/conexion.php';

$venta = new Venta();
session_start();
$vendedor=$_SESSION['usuario'];

if($_POST['funcion']=='registrar_compra'){
    $total=$_POST['total'];
    $nombre=$_POST['nombre'];
    $dni=$_POST['dni'];
    $productos=json_decode($_POST['json']);
    date_default_timezone_set('Europe/Madrid');
    $fecha=date('Y-m-d H:i:s');
    $venta->crear($nombre, $dni, $total, $fecha, $vendedor);
    $venta->ultima_venta();
    foreach ($venta->objetos as $objeto) {
        $id_venta=$objeto->ultima_venta;
        echo $id_venta;
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
                $sql="SELECT * FROM lote WHERE vencimiento = (SELECT MIN(vencimiento) FROM lote WHERE lote_id_prod=:id) and lote_id_prod=:id";
                $query=$conexion->prepare($sql);
                $query->execute(array(':id'=>$prod->id));
                $lote=$query->fetchAll();
                //va recorriendo los lotes desde el más próximo al vencimiento y va restando a la cantidad
                foreach ($lote as $lote) {
                    //cantidad se vuelve 0, pues este lote provee de todos los suministros deseados. La variable cantidad es la que el usuario quiere comprar, no la que hay en stock
                    if($cantidad<$lote->stock){
                        //este sql introduce cada vez que se produce una venta a la tabla detalle_venta la información de la venta a modo de historial
                        $sql="INSERT INTO detalle_venta(det_cantidad, det_vencimiento, id__det_lote, id__det_prod, lote_id_prov, id_det_venta) VALUES('$cantidad', '$lote->vencimiento', '$lote->id_lote', '$prod->id', '$lote->lote_id_prov', '$id_venta')";
                        $conexion->exec($sql);
                        $conexion->exec("UPDATE lote SET stock= stock-'$cantidad' WHERE id_lote ='$lote->id_lote'");
                        $cantidad=0;
                    }
                    //se consume todo el stock, se borra el lote
                    if($cantidad==$lote->stock){
                        $sql="INSERT INTO detalle_venta(det_cantidad, det_vencimiento, id__det_lote, id__det_prod, lote_id_prov, id_det_venta) VALUES('$cantidad', '$lote->vencimiento', '$lote->id_lote', '$prod->id', '$lote->lote_id_prov', '$id_venta')";
                        $conexion->exec($sql);
                        $conexion->exec("DELETE FROM lote WHERE id_lote ='$lote->id_lote'");
                        $cantidad=0;
                    }
                    //la cantidad es superior al stock, por lo tanto todo el lote se consume entero, por tanto se borra
                    if($cantidad>$lote->stock){
                        $sql="INSERT INTO detalle_venta(det_cantidad, det_vencimiento, id__det_lote, id__det_prod, lote_id_prov, id_det_venta) VALUES('$lote->stock', '$lote->vencimiento', '$lote->id_lote', '$prod->id', '$lote->lote_id_prov', '$id_venta')";
                        $conexion->exec($sql);
                        $conexion->exec("DELETE FROM lote WHERE id_lote ='$lote->id_lote'");
                        $cantidad=$cantidad-$lote->stock;
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
