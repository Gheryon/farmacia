<?php
include 'conexion.php';

class Proveedor{
    var $objetos;

    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    function crear($nombre, $telefono, $correo, $direccion, $avatar){
        //se busca si ya existe el proveeodr
        $sql="SELECT id_proveedor FROM proveedor WHERE nombre=:nombre";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':nombre'=>$nombre));
        $this->objetos=$query->fetchAll();
        //si ya existe el nombre del laboratorio, no se añade
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            $sql="INSERT INTO proveedor(nombre, telefono, correo, direccion, avatar) VALUES (:nombre, :telefono, :correo, :direccion, :avatar);";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':nombre'=>$nombre, ':telefono'=>$telefono, ':correo'=>$correo, ':direccion'=>$direccion, ':avatar'=>$avatar));
            echo 'add';
        }
    }

    function buscar()
    {
        //se ha introducido algún caracter a buscar, se devuelven los proveedores que coincidan con la consulta
        if(!empty($_POST['consulta'])){
            $consulta=$_POST['consulta'];
            $sql="SELECT * FROM proveedor WHERE nombre LIKE :consulta";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }else{
            //se devuelven todos los laboratorios; con el NOT LIKE '' se muestran todas las entradas que no son vacías, o sea, todos los registros que existan
            $sql="SELECT * FROM proveedor WHERE nombre NOT LIKE '' ORDER BY id_proveedor desc LIMIT 25";
            $query=$this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }
    }
    function cambiar_logo($id, $nombre)
    {
        $sql="UPDATE proveedor SET avatar=:nombre WHERE id_proveedor=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id, ':nombre'=>$nombre));
    }

    function borrar($id){
        $sql="DELETE FROM proveedor WHERE id_proveedor=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        if(!empty($query->execute(array(':id'=>$id)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }
    }

    function editar($id, $nombre, $telefono, $correo, $direccion){
        //se busca si ya existe algún proveedor que tenga exactamente el mismo nombre, 
        $sql="SELECT id_proveedor FROM proveedor WHERE id_proveedor!=:id AND nombre=:nombre";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id, ':nombre'=>$nombre));
        $this->objetos=$query->fetchAll();
       
        if(!empty($this->objetos)){
            echo 'noedit';
        }else{
            $sql="UPDATE proveedor SET nombre=:nombre, telefono=:telefono, correo=:correo, direccion=:direccion WHERE id_proveedor=:id";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id, ':nombre'=>$nombre, ':telefono'=>$telefono, ':correo'=>$correo, ':direccion'=>$direccion));
            echo 'edit';
        }
    }

    function rellenar_proveedores(){
        $sql="SELECT * FROM proveedor order by nombre ASC";
        $query=$this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }
}
?>