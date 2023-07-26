<?php
date_default_timezone_set("America/Lima");
class Conexion{

	public static function conectar(){

		$link = new PDO("mysql:host=localhost;dbname=beltaplussize",
			            "root",
			            "");

		$link->exec("set names utf8");

		return $link;

	}

}