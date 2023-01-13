<?php
include_once 'conexion.php';

class Cliente{
  var $objetos;

  public function __construct(){
    $db = new Conexion();
    $this->acceso = $db->pdo;
  }

  function buscar(){
    //se ha introducido algún caracter a buscar, se devuelven los proveedores que coincidan con la consulta
    if(!empty($_POST['consulta'])){
      $consulta=$_POST['consulta'];
      $sql="SELECT * FROM cliente WHERE estado='A' and nombre LIKE :consulta";
      $query=$this->acceso->prepare($sql);
      $query->execute(array(':consulta'=>"%$consulta%"));
      $this->objetos=$query->fetchAll();
      return $this->objetos;
    }else{
      //se devuelven todos los laboratorios; con el NOT LIKE '' se muestran todas las entradas que no son vacías, o sea, todos los registros que existan
      $sql="SELECT * FROM cliente WHERE estado='A' and nombre NOT LIKE '' ORDER BY dni desc LIMIT 25";
      $query=$this->acceso->prepare($sql);
      $query->execute();
      $this->objetos=$query->fetchAll();
      return $this->objetos;
    }
  }

  function crear($nombre, $apellidos, $dni, $edad, $telefono, $correo, $sexo, $adicional, $avatar){
    //se busca si ya existe el proveeodr
    $sql="SELECT id,estado FROM cliente WHERE nombre=:nombre AND apellidos=:apellidos AND dni=:dni";
    $query=$this->acceso->prepare($sql);
    $query->execute(array(':nombre'=>$nombre, ':apellidos'=>$apellidos, ':dni'=>$dni));
    $this->objetos=$query->fetchAll();
    //si ya existe, no se añade
    if(!empty($this->objetos)){
      foreach ($this->objetos as $cli) {
        $cli_id=$cli->id_proveedor;
        $cli_estado=$cli->estado;
      }
      if($cli_estado=='A'){
        echo 'noadd';
      }else{
        $sql="UPDATE cliente SET estado='A' WHERE id=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$cli_id)); 
        echo 'add';
      }
    }else{
      $sql="INSERT INTO cliente(nombre, apellidos, dni, edad, telefono, correo, sexo, adicional, avatar) VALUES (:nombre, :apellidos, :dni, :edad, :telefono, :correo, :sexo, :adicional, :avatar);";
      $query=$this->acceso->prepare($sql);
      $query->execute(array(':nombre'=>$nombre, ':apellidos'=>$apellidos, ':dni'=>$dni, ':edad'=>$edad, ':telefono'=>$telefono, ':correo'=>$correo, ':sexo'=>$sexo, ':adicional'=>$adicional, ':avatar'=>$avatar));
      echo 'add';
    }
  }

  function editar($id, $telefono, $correo, $adicional){
    //se busca si ya existe algún cliente que tenga el mismo id 
    $sql="SELECT id FROM cliente WHERE id=:id";
    $query=$this->acceso->prepare($sql);
    $query->execute(array(':id'=>$id));
    $this->objetos=$query->fetchAll();
    
    if(empty($this->objetos)){
        echo 'noedit';
    }else{
      $sql="UPDATE cliente SET telefono=:telefono, correo=:correo, adicional=:adicional WHERE id=:id";
      $query=$this->acceso->prepare($sql);
      $query->execute(array(':id'=>$id, ':telefono'=>$telefono, ':correo'=>$correo, ':adicional'=>$adicional));
      echo 'edit';
    }
  }

  function borrar($id){
    $sql="UPDATE cliente SET estado='I' WHERE id=:id";
    $query=$this->acceso->prepare($sql);
    $query->execute(array(':id'=>$id));
    if(!empty($query->execute(array(':id'=>$id)))){
        echo 'borrado';
    }else{
        echo 'noborrado';
    }
  }

  function rellenar_clientes(){
    $sql="SELECT * FROM cliente WHERE estado='A' ORDER BY nombre asc";
    $query=$this->acceso->prepare($sql);
    $query->execute();
    $this->objetos=$query->fetchAll();
    return $this->objetos;
  }

  function buscar_datos_cliente($id_cliente){
    $sql="SELECT * FROM cliente WHERE id=:id_cliente";
    $query=$this->acceso->prepare($sql);
    $query->execute(array(':id_cliente'=>$id_cliente));
    $this->objetos=$query->fetchAll();
    return $this->objetos;
  }
}
?>