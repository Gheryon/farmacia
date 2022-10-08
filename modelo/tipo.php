<?php
include 'conexion.php';

class Tipo{
    var $objetos;

    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    function crear($nombre){
        //se busca si ya existe el laboratorio
        $sql="SELECT id_tip_prod FROM tipo_producto WHERE nombre=:nombre";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':nombre'=>$nombre));
        $this->objetos=$query->fetchAll();
        //si ya existe el nombre del laboratorio, no se añade
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            $sql="INSERT INTO tipo_producto(nombre) VALUES (:nombre);";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':nombre'=>$nombre));
            echo 'add';
        }
    }

    function buscar()
    {
        //se ha introducido algún caracter a buscar, se devuelven los laboratorios que encagen con la consulta
        if(!empty($_POST['consulta'])){
            $consulta=$_POST['consulta'];
            $sql="SELECT * FROM tipo_producto WHERE nombre LIKE :consulta";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }else{
            //se devuelven todos los laboratorios; con el NOT LIKE '' se muestran todas las entradas que no son vacías, o sea, todos los registros que existan
            $sql="SELECT * FROM tipo_producto WHERE nombre NOT LIKE '' ORDER BY id_tip_prod LIMIT 25";
            $query=$this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }
    }
    function borrar($id){
        $sql="DELETE FROM tipo_producto WHERE id_tip_prod=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        if(!empty($query->execute(array(':id'=>$id)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }
    }

    function editar($nombre, $id_editado){
        $sql="UPDATE tipo_producto SET nombre=:nombre WHERE id_tip_prod=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':nombre'=>$nombre, ':id'=>$id_editado));
        echo 'edit';
    }

    function rellenar_tipos(){
        $sql="SELECT * FROM tipo_producto order by nombre ASC";
        $query=$this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }
}
?>