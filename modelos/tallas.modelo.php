<?php

require_once "conexion.php";

class ModeloTallas{
     /*=============================================
	MOSTRAR TALLAS
	=============================================*/

	public static function mdlMostrarTallas($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY talla");
			$stmt -> execute();
			return $stmt -> fetchAll();

		}

		$stmt -> close();
		$stmt = null;

	}

	/*=============================================
	CREAR TALLA
	=============================================*/

	public static function mdlIngresarTalla($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(talla) VALUES(UPPER(:talla))");
		$stmt->bindParam(":talla", $datos, PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}
		$stmt->close();
		$stmt = null;
	}

	/*=============================================
	EDITAR TALLA
	=============================================*/

	public static function mdlEditarTalla($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET talla = UPPER(:talla) WHERE id = :id");
		$stmt -> bindParam(":talla", $datos["talla"], PDO::PARAM_STR);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;
	}

	/*=============================================
	BORRAR TALLA
	=============================================*/

	public static function mdlBorrarTalla($tabla, $datos){

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
} // Fin de la Clase