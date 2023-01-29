<?php
include_once 'conexion.php';

class Compras{
  var $objetos;

  public function __construct(){
    $db = new Conexion();
    $this->acceso = $db->pdo;
  }

  function crear($codigo, $fecha_compra, $fecha_entrega, $total, $estado, $proveedor){
    $sql="INSERT INTO compra(codigo, fecha_compra, fecha_entrega, total, id_estado_pago, id_proveedor) VALUES (:codigo, :fecha_compra, :fecha_entrega, :total, :id_estado_pago, :id_proveedor);";
    $query=$this->acceso->prepare($sql);
    $query->execute(array(':codigo'=>$codigo, ':fecha_compra'=>$fecha_compra, ':fecha_entrega'=>$fecha_entrega, ':total'=>$total, ':id_estado_pago'=>$estado, ':id_proveedor'=>$proveedor));
  }

  function ultima_compra(){
    $sql="SELECT MAX(id) as ultima_compra FROM compra";
    $query=$this->acceso->prepare($sql);
    $query->execute(array());
    $this->objetos=$query->fetchAll();
    return $this->objetos;
  }

  function mostrar_compras(){
    //se necesitan también datos de estado de pago y de proveedores, que están en otras tablas
    $sql="SELECT concat(c.id,' | ', c.codigo) as codigo, fecha_compra, fecha_entrega, total, e.nombre as estado, p.nombre as proveedor FROM compra as c
    JOIN estado_pago as e ON e.id = c.id_estado_pago
    JOIN proveedor AS p ON p.id_proveedor = c.id_proveedor";
    $query=$this->acceso->prepare($sql);
    $query->execute(array());
    $this->objetos=$query->fetchAll();
    return $this->objetos;
  }

  function cambiar_estado($id_estado, $id_compra){
    $sql="UPDATE compra SET id_estado_pago=:id_estado WHERE id=:id_compra";
    $query=$this->acceso->prepare($sql);
    $query->execute(array(':id_estado'=>$id_estado, ':id_compra'=>$id_compra));
    $this->objetos=$query->fetchAll();
    return $this->objetos;
  }
}
?>