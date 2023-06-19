<?php

require_once "conexion.php";

class ModeloMotivos{

	/*=============================================
	CREAR MOTIVO
	=============================================*/

	public static function mdlIngresarMotivo($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(motivo) VALUES (:motivo)");

		$stmt->bindParam(":motivo", $datos, PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	MOSTRAR MOTIVOS
	=============================================*/

	public static function mdlMostrarMotivos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	EDITAR MOTIVO
	=============================================*/

	public static function mdlEditarMotivo($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET motivo = :motivo WHERE id = :id");

		$stmt -> bindParam(":motivo", $datos["motivo"], PDO::PARAM_STR);
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
	BORRAR MOTIVO
	=============================================*/

	public static function mdlBorrarMotivo($tabla, $datos){

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

