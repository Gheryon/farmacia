<?php
include_once 'conexion.php';

class Estado{
  var $objetos;

  public function __construct(){
    $db = new Conexion();
    $this->acceso = $db->pdo;
  }

  function rellenar_estado(){
    $sql="SELECT * FROM estado_pago";
    $query=$this->acceso->prepare($sql);
    $query->execute();
    $this->objetos=$query->fetchAll();
    return $this->objetos;
  }
}
?>