<?php
include 'conexion.php';

class VentaProducto{
    var $objetos;

    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    function crear($nombre, $dni, $total, $fecha, $vendedor){
        $sql="INSERT INTO venta(fecha,cliente,dni,total,vendedor) VALUES (:fecha, :cliente, :dni, :total, :vendedor)";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':fecha'=>$fecha, ':cliente'=>$nombre, ':dni'=>$dni, ':total'=>$total, ':vendedor'=>$vendedor));
    }

    function ultima_venta(){
        $sql="SELECT MAX(id_venta) as ultima_venta FROM venta";
        $query=$this->acceso->prepare($sql);
        $query->execute(array());
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }

    function borrar($id_venta){
        $sql="DELETE FROM venta WHERE id_venta=:id_venta";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id_venta'=>$id_venta));
    }

    function buscar($id){
        $sql="SELECT venta_producto.precio as precio, cantidad, producto.nombre as producto, concentracion, adicional, laboratorio.nombre as laboratorio, presentacion.nombre as presentacion, tipo_producto.nombre as tipo, subtotal FROM venta_producto JOIN producto on producto_id_producto=id_producto AND venta_id_venta=:id JOIN laboratorio on prod_lab=id_laboratorio JOIN tipo_producto on prod_tip_prod=id_tip_prod JOIN presentacion on prod_present=id_presentacion";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }
}
?>