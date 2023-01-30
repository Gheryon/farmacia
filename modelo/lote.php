<?php
include_once 'conexion.php';

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
		$sql="UPDATE lote set cantidad_lote=:stock WHERE id=:id;";
		$query=$this->acceso->prepare($sql);
		$query->execute(array(':stock'=>$stock, ':id'=>$id));
		echo 'edit';
	}

	function borrarLote($id){
			$sql="UPDATE lote SET estado='I' WHERE id=:id";
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
			$sql="SELECT l.id as id_lote, concat(l.id,' | ',l.codigo) as codigo, l.cantidad_lote, vencimiento, concentracion, adicional, producto.nombre as prod_nom, laboratorio.nombre as lab_nom, tipo_producto.nombre as tip_nom, presentacion.nombre as pre_nom, proveedor.nombre as prov_nom, producto.avatar as logo 
			FROM lote as l
			JOIN compra on l.id_compra=compra.id AND l.estado='A'
			JOIN proveedor on proveedor.id_proveedor=compra.id_proveedor
			JOIN producto on producto.id_producto=l.id_producto
			JOIN laboratorio on prod_lab=id_laboratorio
			JOIN tipo_producto on prod_tip_prod=id_tip_prod
			JOIN presentacion on prod_present=id_presentacion AND producto.nombre LIKE :consulta ORDER BY producto.nombre LIMIT 25;";
			$query=$this->acceso->prepare($sql);
			$query->execute(array(':consulta'=>"%$consulta%"));
			$this->objetos=$query->fetchAll();
			return $this->objetos;
		}else{
			//se devuelven todos los laboratorios; con el NOT LIKE '' se muestran todas las entradas que no son vacías, o sea, todos los registros que existan
			$sql="SELECT l.id as id_lote, concat(l.id,' | ',l.codigo) as codigo, l.cantidad_lote, vencimiento, concentracion, adicional, producto.nombre as prod_nom, laboratorio.nombre as lab_nom, tipo_producto.nombre as tip_nom, presentacion.nombre as pre_nom, proveedor.nombre as prov_nom, producto.avatar as logo 
			FROM lote as l
			JOIN compra on l.id_compra=compra.id AND l.estado='A'
			JOIN proveedor on proveedor.id_proveedor=compra.id_proveedor
			JOIN producto on producto.id_producto=l.id_producto
			JOIN laboratorio on prod_lab=id_laboratorio
			JOIN tipo_producto on prod_tip_prod=id_tip_prod
			JOIN presentacion on prod_present=id_presentacion AND producto.nombre NOT LIKE '' ORDER BY producto.nombre LIMIT 25;";
			$query=$this->acceso->prepare($sql);
			$query->execute();
			$this->objetos=$query->fetchAll();
			return $this->objetos;
		}
	}

	/////////////funciones actualizadas//////////////////
	function crear_lote($codigo, $cantidad, $vencimiento, $precio_compra, $id_compra, $id_producto){
			$sql="INSERT INTO lote(codigo, cantidad, cantidad_lote, vencimiento, precio_compra, id_compra, id_producto) VALUES (:codigo, :cantidad, :cantidad_lote, :vencimiento, :precio_compra, :id_compra, :id_producto);";
			$query=$this->acceso->prepare($sql);
			$query->execute(array(':codigo'=>$codigo, ':cantidad'=>$cantidad, ':cantidad_lote'=>$cantidad, ':vencimiento'=>$vencimiento, ':precio_compra'=>$precio_compra, ':id_compra'=>$id_compra, ':id_producto'=>$id_producto));
			echo 'add';
	}

	function ver($id){
			$sql="SELECT l.codigo as codigo, l.cantidad as cantidad, vencimiento, precio_compra, p.nombre as producto, concentracion, adicional, lab.nombre as laboratorio, t.nombre as tipo, pre.nombre as presentacion
			FROM lote as l
			JOIN producto as p on l.id_producto=p.id_producto AND id_compra=:id
			JOIN laboratorio as lab on prod_lab=id_laboratorio
			JOIN tipo_producto as t on prod_tip_prod=id_tip_prod
			JOIN presentacion as pre on prod_present=id_presentacion;";
			$query=$this->acceso->prepare($sql);
			$query->execute(array(':id'=>$id));
			$this->objetos=$query->fetchAll();
			return $this->objetos;
	}
}

?>