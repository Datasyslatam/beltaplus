<?php
require_once "conexion.php";

class ModeloColores{

    /*=============================================
	MOSTRAR COLORES
	=============================================*/

	public static function mdlMostrarColores($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY color");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}

		$stmt -> close();
		$stmt = null;

	}

	/*=============================================
	CREAR COLOR
	=============================================*/

	public static function mdlIngresarColor($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(color) VALUES (UPPER(:color))");
		$stmt->bindParam(":color", $datos, PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;
	}

	/*=============================================
	EDITAR COLOR
	=============================================*/

	public static function mdlEditarColor($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET color = UPPER(:color) WHERE id = :id");
		$stmt -> bindParam(":color", $datos["color"], PDO::PARAM_STR);
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
	BORRAR COLOR
	=============================================*/

	public static function mdlBorrarColor($tabla, $datos){

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

}