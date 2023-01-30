<?php
include_once 'conexion.php';

class Venta{
    var $objetos;

    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    function crear($cliente, $total, $fecha, $vendedor){
        $sql="INSERT INTO venta(fecha,total,vendedor,id_cliente) VALUES (:fecha, :total, :vendedor, :cliente)";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':fecha'=>$fecha, ':total'=>$total, ':vendedor'=>$vendedor, ':cliente'=>$cliente));
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

    function buscar(){
        $sql="SELECT id_venta, fecha, cliente, dni, total, CONCAT(usuario.nombre_us,' ',usuario.apellidos_us) as vendedor,id_cliente FROM venta JOIN usuario on vendedor=id_usuario";
        $query=$this->acceso->prepare($sql);
        $query->execute(array());
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }

    function recuperar_vendedor($id_venta){
        $sql="SELECT us_tipo FROM venta JOIN usuario on id_usuario=vendedor WHERE id_venta=:ud_venta";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id_venta'=>$id_venta));
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }

    function venta_dia_vendedor($id_usuario){
        $sql="SELECT SUM(total) as venta_dia_vendedor FROM `venta` WHERE vendedor=:id_usuario AND date(fecha)=date(curdate()); ";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id_usuario'=>$id_usuario));
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }

    function venta_diaria(){
        $sql="SELECT SUM(total) as venta_diaria FROM `venta` WHERE date(fecha)=date(curdate()); ";
        $query=$this->acceso->prepare($sql);
        $query->execute(array());
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }

	//se necesita especificar que el año y el mes sean el actual, sino traerá todos los meses de todos los años
	function venta_mensual(){
		$sql="SELECT SUM(total) as venta_mensual FROM `venta` WHERE year(fecha)=year(curdate()) and month(fecha)=month(curdate()); ";
		$query=$this->acceso->prepare($sql);
		$query->execute(array());
		$this->objetos=$query->fetchAll();
		return $this->objetos;
	}

	function venta_anual(){
		$sql="SELECT SUM(total) as venta_anual FROM `venta` WHERE year(fecha)=year(curdate()); ";
		$query=$this->acceso->prepare($sql);
		$query->execute(array());
		$this->objetos=$query->fetchAll();
		return $this->objetos;
	}

	function ganancia_mensual(){
		$sql="SELECT SUM(det_cantidad*precio_compra) as ganancia_mensual FROM detalle_venta
		JOIN venta on id_det_venta=id_venta
		AND year(fecha)=year(curdate()) AND month(fecha)=month(curdate())
		JOIN lote on id__det_lote=lote.id;";
		$query=$this->acceso->prepare($sql);
		$query->execute(array());
		$this->objetos=$query->fetchAll();
		return $this->objetos;
	}
}
?>