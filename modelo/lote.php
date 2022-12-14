<?php
include 'conexion.php';

class Lote{
    var $objetos;

    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    function crearLote($id_producto, $proveedor, $stock, $vencimiento){
        $sql="INSERT INTO lote(stock, vencimiento, lote_id_prov, lote_id_prod) VALUES (:stock, :vencimiento, :id_proveedor, :id_producto);";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':stock'=>$stock, ':vencimiento'=>$vencimiento, ':id_producto'=>$id_producto, ':id_proveedor'=>$proveedor));
        echo 'add';
    }

    function editarLote($id, $stock){
        $sql="UPDATE lote set stock=:stock WHERE id_lote=:id;";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':stock'=>$stock, ':id'=>$id));
        echo 'edit';
    }

    function borrarLote($id){
        $sql="DELETE FROM lote WHERE id_lote=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        if(!empty($query->execute(array(':id'=>$id)))){
            echo 'borrado';
        }else{
            echo 'noborrado';
        }
    }
    function buscar(){
        if(!empty($_POST['consulta'])){
            $consulta=$_POST['consulta'];
            $sql="SELECT id_lote, stock, vencimiento, concentracion, adicional, producto.nombre as prod_nom, laboratorio.nombre as lab_nom, tipo_producto.nombre as tip_nom, presentacion.nombre as pre_nom, proveedor.nombre as prov_nom, producto.avatar as logo FROM `lote` 
            JOIN proveedor on lote_id_prov=id_proveedor
            JOIN producto on lote_id_prod=id_producto
            JOIN laboratorio on prod_lab=id_laboratorio
            JOIN tipo_producto on prod_tip_prod=id_tip_prod
            JOIN presentacion on prod_present=id_presentacion AND producto.nombre LIKE :consulta ORDER BY producto.nombre LIMIT 25;";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }else{
            //se devuelven todos los laboratorios; con el NOT LIKE '' se muestran todas las entradas que no son vac??as, o sea, todos los registros que existan
            $sql="SELECT id_lote, stock, vencimiento, concentracion, adicional, producto.nombre as prod_nom, laboratorio.nombre as lab_nom, tipo_producto.nombre as tip_nom, presentacion.nombre as pre_nom, proveedor.nombre as prov_nom, producto.avatar as logo FROM `lote` 
            JOIN proveedor on lote_id_prov=id_proveedor
            JOIN producto on lote_id_prod=id_producto
            JOIN laboratorio on prod_lab=id_laboratorio
            JOIN tipo_producto on prod_tip_prod=id_tip_prod
            JOIN presentacion on prod_present=id_presentacion AND producto.nombre NOT LIKE '' ORDER BY producto.nombre LIMIT 25;";
            $query=$this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }
    }
}

?>