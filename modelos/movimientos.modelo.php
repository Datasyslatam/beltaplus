<?php

require_once "conexion.php";

class ModeloMovimientos{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	public static function mdlMostrarMovimientos($tabla, $item, $valor, $orden){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item=:$item ORDER BY id DESC");			            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $orden DESC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}
	
	
	/*=============================================
	MOSTRAR PRODUCTOS MOVIMIENTOS
	=============================================*/

	public static function mdlMostrarMovimientosKardex($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item=:$item ORDER BY id_historial DESC");			            
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetchAll();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}
	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/
	public static function mdlIngresarMovimiento($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, codigo, descripcion, imagen, stock, precio_compra, precio_venta) VALUES (:id_categoria, :codigo, :descripcion, :imagen, :stock, :precio_compra, :precio_venta)");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);

		if($stmt->execute()){
			return "ok";
		}else{
			return "error";
		}
		$stmt->close();
		$stmt = null;
	}

	/*=============================================
	INGRESO PRODUCTO
	=============================================*/
	public static function mdlIngresoMovimiento($tabla, $datos){
		$historial = "historial";
		$fecha = date("Y-m-d H:i:s");
		$nota = "Ingreso";
		//mostrar stock
		$detail = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id=:id");
		$detail->bindParam(":id", $datos["id"], PDO::PARAM_INT);
	    $detail->execute();
		$data = $detail->fetch();
		$total = $data["stock"]+$datos["ingresoStock"];
		//fin:mostrar stock
		//Registrar Kardex
		$registro = Conexion::conectar()->prepare("INSERT INTO $historial(id_producto, user_id, fecha, nota, referencia,	cantidad) VALUES (:id_producto, :user_id, :fecha, :nota, :referencia, :cantidad )");
		$registro->bindParam(":id_producto", $datos["id"], PDO::PARAM_INT);
		$registro->bindParam(":user_id", $_SESSION["id"], PDO::PARAM_INT);
		$registro->bindParam(":fecha", $fecha, PDO::PARAM_STR);
		$registro->bindParam(":nota", $nota, PDO::PARAM_STR);
		$registro->bindParam(":referencia", $datos["ingresoMotivo"], PDO::PARAM_STR);
		$registro->bindParam(":cantidad", $datos["ingresoStock"], PDO::PARAM_INT);
	    $registro->execute();
		//fin:Registrar Kardex
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET stock = :stock WHERE id = :idc");
		$stmt->bindParam(":stock", $total, PDO::PARAM_INT);
		$stmt->bindParam(":idc", $datos["id"], PDO::PARAM_INT);
		if($stmt->execute()){
			return "ok";
		}else{
			return "error";
		}
		$stmt->close();
		$stmt = null;

	}
	/*=============================================
	INGRESO PRODUCTO VENTAS
	=============================================*/
	public static function mdlIngresoMovimientoVenta($tablaHistorial, $cant, $idp){
		$fecha = date("Y-m-d H:i:s");
		$nota = "Venta";
		
		//Registrar Kardex
		$registro = Conexion::conectar()->prepare("INSERT INTO $tablaHistorial(id_producto, user_id, fecha, nota, cantidad) VALUES (:id_producto, :user_id, :fecha, :nota, :cantidad )");
		$registro->bindParam(":id_producto", $idp, PDO::PARAM_INT);
		$registro->bindParam(":user_id", $_SESSION["id"], PDO::PARAM_INT);
		$registro->bindParam(":fecha", $fecha, PDO::PARAM_STR);
		$registro->bindParam(":nota", $nota, PDO::PARAM_STR);
		/*$registro->bindParam(":referencia", $datos["ingresoMotivo"], PDO::PARAM_STR);*/
		$registro->bindParam(":cantidad", $cant, PDO::PARAM_INT);
	   /* $registro->execute();*/
		//fin:Registrar Kardex
		/*$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET stock = :stock WHERE id = :idc");
		$stmt->bindParam(":stock", $total, PDO::PARAM_INT);
		$stmt->bindParam(":idc", $datos["id"], PDO::PARAM_INT);*/
		if($registro->execute()){
			return "ok";
		}else{
			return "error";
		}
		$registro->close();
		$registro = null;

	}	
	
	/*=============================================
	SALIDA PRODUCTO
	=============================================*/
	public static function mdlSalidaMovimiento($tabla, $datos){
		$historial = "historial";
		$fecha = date("Y-m-d H:i:s");
		$nota = "Salida";
		//mostrar stock
		$detail = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id=:id");
		$detail->bindParam(":id", $datos["id"], PDO::PARAM_INT);
	    $detail->execute();
		$data = $detail->fetch();
		$total = $data["stock"]-$datos["salidaStock"];
		//fin:mostrar stock
		//Registrar Kardex
		$registro = Conexion::conectar()->prepare("INSERT INTO $historial(id_producto, user_id, fecha, nota, referencia,	cantidad) VALUES (:id_producto, :user_id, :fecha, :nota, :referencia, :cantidad )");
		$registro->bindParam(":id_producto", $datos["id"], PDO::PARAM_INT);
		$registro->bindParam(":user_id", $_SESSION["id"], PDO::PARAM_INT);
		$registro->bindParam(":fecha", $fecha, PDO::PARAM_STR);
		$registro->bindParam(":nota", $nota, PDO::PARAM_STR);
		$registro->bindParam(":referencia", $datos["salidaMotivo"], PDO::PARAM_STR);
		$registro->bindParam(":cantidad", $datos["salidaStock"], PDO::PARAM_INT);
	    $registro->execute();
		//fin:Registrar Kardex
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET stock = :stock WHERE id = :idc");
		$stmt->bindParam(":stock", $total, PDO::PARAM_INT);
		$stmt->bindParam(":idc", $datos["id"], PDO::PARAM_INT);
		if($stmt->execute()){
			return "ok";
		}else{
			return "error";
		}

		$stmt->close();
		$stmt = null;

	}
	/*=============================================
	BORRAR PRODUCTO
	=============================================*/

	public static function mdlEliminarMovimiento($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	public static function mdlActualizarMovimiento($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/	

	public static function mdlMostrarSumaVentas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;
	}


}