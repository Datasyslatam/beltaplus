<?php

require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

class AjaxClientes{

	/*=============================================
	EDITAR CLIENTE
	=============================================*/	

	public $idCliente;

	public function ajaxEditarCliente(){

		$item = "id";
		$valor = $this->idCliente;

		$respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

		echo json_encode($respuesta);


	}
	
	public function ajaxFiltrarClientes(){

		$item = $_POST["item"];
		$valor = $_POST["valor"];

		$respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

		echo json_encode($respuesta);


	}

	public function ajaxGuardarNuevoCliente() {
		$respuesta = ControladorClientes::ctrCrearNuevoCliente();
		echo json_encode($respuesta);
	}

	public function ajaxMostrarClientes(){
		$item = null;
		$valor = null;
		$respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);
		
		echo json_encode($respuesta);
	}

}


/*=============================================
CREAR CLIENTE
=============================================*/	

if(isset($_POST["guardar"])){

	$cliente = new AjaxClientes();
	$cliente -> ajaxGuardarNuevoCliente();

}

/*=============================================
MOSTRAR CLIENTES
=============================================*/	

if(isset($_POST["mostrarClientes"])){

	$cliente = new AjaxClientes();
	$cliente -> ajaxMostrarClientes();

}

/*=============================================
EDITAR CLIENTE
=============================================*/	

if(isset($_POST["idCliente"])){

	$cliente = new AjaxClientes();
	$cliente -> idCliente = $_POST["idCliente"];
	$cliente -> ajaxEditarCliente();

}


/*=============================================
FILTRAR CLIENTES
=============================================*/	

if(isset($_POST["filtrarCliente"])){

	$cliente = new AjaxClientes();
	$cliente -> ajaxFiltrarClientes();

}