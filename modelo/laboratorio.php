<?php
include 'conexion.php';

class Laboratorio{
    var $objetos;

    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    function crear($nombre, $avatar){
        //se busca si ya existe el laboratorio
        $sql="SELECT id_laboratorio FROM laboratorio WHERE nombre=:nombre";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':nombre'=>$nombre));
        $this->objetos=$query->fetchAll();
        //si ya existe el nombre del laboratorio, no se añade
        if(!empty($this->objetos)){
            echo 'noadd';
        }else{
            $sql="INSERT INTO laboratorio(nombre, avatar) VALUES (:nombre, :avatar);";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':nombre'=>$nombre, ':avatar'=>$avatar));
            echo 'add';
        }
    }

    function buscar()
    {
        //se ha introducido algún caracter a buscar, se devuelven los laboratorios que encagen con la consulta
        if(!empty($_POST['consulta'])){
            $consulta=$_POST['consulta'];
            $sql="SELECT * FROM laboratorio WHERE nombre LIKE :consulta";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }else{
            //se devuelven todos los laboratorios; con el NOT LIKE '' se muestran todas las entradas que no son vacías, o sea, todos los registros que existan
            $sql="SELECT * FROM laboratorio WHERE nombre NOT LIKE '' ORDER BY id_laboratorio LIMIT 25";
            $query=$this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }
    }
    function cambiar_logo($id, $nombre)
    {
        //primero se consulta si la contraseña actual es correcta
        $sql="SELECT avatar FROM laboratorio WHERE id_laboratorio=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $this->objetos=$query->fetchAll();
        
            $sql="UPDATE laboratorio SET avatar=:nombre WHERE id_laboratorio=:id";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id, ':nombre'=>$nombre));
        
        return $this->objetos;
    }
    function borrar($id){
        $sql="DELETE FROM laboratorio WHERE id_Laboratorio=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        if(!empty($query->execute(array(':id'=>$id)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }
    }

    function editar($nombre, $id_editado){
        $sql="UPDATE laboratorio SET nombre=:nombre WHERE id_Laboratorio=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':nombre'=>$nombre, ':id'=>$id_editado));
        echo 'edit';
    }

    function rellenar_laboratorios(){
        $sql="SELECT * FROM laboratorio order by nombre ASC";
        $query=$this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }
}
?>