<?php
date_default_timezone_set("America/Lima");
class Conexion{

	public static function conectar(){

		/* $link = new PDO("mysql:host=localhost;dbname=beltaplussize",
			            "root", ""); */

		 $link = new PDO("mysql:host=localhost;dbname=u798057814_beltapluss",
			            "u798057814_beltap",
			            "Ghy1Be4l");

		$link->exec("set names utf8");

		return $link;

	}

}
