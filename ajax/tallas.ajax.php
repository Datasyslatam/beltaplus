<?php

require_once "../controladores/tallas.controlador.php";
require_once "../modelos/tallas.modelo.php";

class AjaxTallas{

	/*=============================================
	EDITAR TALLA
	=============================================*/	

	public $idTalla;
	public function ajaxEditarTalla(){

		$item = "id";
		$valor = $this->idTalla;

		$respuesta = ControladorTallas::ctrMostrarTallas($item, $valor);

		echo json_encode($respuesta);
	}
}

/*=============================================
EDITAR TALLA
=============================================*/	
if(isset($_POST["idTalla"])){

	$talla = new AjaxTallas();
	$talla -> idTalla = $_POST["idTalla"];
	$talla -> ajaxEditarTalla();
}
