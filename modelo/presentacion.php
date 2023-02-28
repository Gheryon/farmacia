<?php
include 'conexion.php';

class Presentacion{
    var $objetos;

    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    function crear($nombre){
        //se busca si ya existe 
        $sql="SELECT id_presentacion,estado FROM presentacion WHERE nombre=:nombre";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':nombre'=>$nombre));
        $this->objetos=$query->fetchAll();
        //si ya existe , no se añade
        if(!empty($this->objetos)){
            foreach ($this->objetos as $pre) {
                $pre_id=$pre->id_presentacion;
                $pre_estado=$pre->estado;
            }
            if($pre_estado=='A'){
                echo 'noadd';
            }else{
                $sql="UPDATE presentacion SET estado='A' WHERE id_presentacion=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$pre_id)); 
                echo 'add';
            }
        }else{
            $sql="INSERT INTO presentacion(nombre) VALUES (:nombre);";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':nombre'=>$nombre));
            echo 'add';
        }
    }

    function buscar(){
        //se ha introducido algún caracter a buscar, se devuelven los laboratorios que encagen con la consulta
        if(!empty($_POST['consulta'])){
            $consulta=$_POST['consulta'];
            $sql="SELECT * FROM presentacion WHERE estado='A' and nombre LIKE :consulta";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }else{
            //se devuelven todos los laboratorios; con el NOT LIKE '' se muestran todas las entradas que no son vacías, o sea, todos los registros que existan
            $sql="SELECT * FROM presentacion WHERE estado='A' and nombre NOT LIKE '' ORDER BY id_presentacion LIMIT 25";
            $query=$this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }
    }

    function borrar($id){
        $sql="SELECT * FROM producto WHERE prod_present=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $pre=$query->fetchAll();
        if(!empty($pre)){
            echo 'noborrado';
        }else{
            $sql="UPDATE presentacion SET estado='I' WHERE id_presentacion=:id";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id));
            if(!empty($query->execute(array(':id'=>$id)))){
                echo 'borrado';
            }else{
                echo 'noborrado';
            }
        }
    }

    function editar($nombre, $id_editado){
        $sql="UPDATE presentacion SET nombre=:nombre WHERE id_presentacion=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':nombre'=>$nombre, ':id'=>$id_editado));
        echo 'edit';
    }

    function rellenar_presentaciones(){
        $sql="SELECT * FROM presentacion WHERE estado='A' order by nombre ASC";
        $query=$this->acceso->prepare($sql);
        $query->execute();
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }
}
?>