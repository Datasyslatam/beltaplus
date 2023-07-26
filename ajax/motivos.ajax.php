<?php

require_once "../controladores/motivos.controlador.php";
require_once "../modelos/motivos.modelo.php";

class AjaxMotivos{

	/*=============================================
	EDITAR CATEGORÍA
	=============================================*/	

	public $idMotivo;

	public function ajaxEditarMotivo(){

		$item = "id";
		$valor = $this->idMotivo;

		$respuesta = ControladorMotivos::ctrMostrarMotivos($item, $valor);

		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idMotivo"])){

	$motivo = new AjaxMotivos();
	$motivo -> idMotivo = $_POST["idMotivo"];
	$motivo -> ajaxEditarMotivo();
}
