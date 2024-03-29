<?php
class ControladorClientes{
	/*=============================================
	CREAR CLIENTES
	=============================================*/
	public static function ctrCrearCliente(){
		if(isset($_POST["nuevoCliente"])){
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevoDocumentoId"]) &&
			   preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"])
			   ){
			   	$tabla = "clientes";
			   	$datos = array("nombre"=>$_POST["nuevoCliente"],
					           "documento"=>$_POST["nuevoDocumentoId"],
					           "email"=>$_POST["nuevoEmail"],
					           "telefono"=>$_POST["nuevoTelefono"],
					           "direccion"=>$_POST["nuevaDireccion"],
							   "ciudad"=>$_POST["nuevaCiudad"]
					           );
			   	$respuesta = ModeloClientes::mdlIngresarCliente($tabla, $datos);
			   	if($respuesta == "ok"){
					echo'<script>
					swal({
						  type: "success",
						  title: "El cliente ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "clientes";
									}
								})
					</script>';
				}
			}else{
				echo'<script>
					swal({
						  type: "error",
						  title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "clientes";
							}
						})
			  	</script>';
			}
		}
	}

	/*=============================================
	CREAR NUEVO CLIENTES DESDE VENTAS
	=============================================*/
	public static function ctrCrearNuevoCliente(){
		if(isset($_POST["nuevoCliente"])){
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevoDocumentoId"]) 
			   ){
			   	$tabla = "clientes";

				//Validar existencia de clientes
				$item = "documento";
				$valor = $_POST["nuevoDocumentoId"];
				$existe_usuario = ControladorClientes::ctrMostrarClientes($item, $valor);
				if ($existe_usuario) {
					return ["error" => 1, "msg" => "Ya existe un cliente con este documento de identidad"];	
				}

				$datos = array("nombre"=>$_POST["nuevoCliente"],
								"documento"=>$_POST["nuevoDocumentoId"],
								"email"=>$_POST["nuevoEmail"],
								"telefono"=>$_POST["nuevoTelefono"],
								"direccion"=>$_POST["nuevaDireccion"],
								"ciudad"=>$_POST["nuevaCiudad"]
								);
				
				$respuesta = ModeloClientes::mdlIngresarClienteVenta($tabla, $datos);
			   	if($respuesta != "error"){
					return ["error" => 0, "msg" => "El cliente ha sido guardado correctamente", "data" => $respuesta];
				}
			}else{
				return ["error" => 1, "msg" => "¡El cliente no puede ir vacío o llevar caracteres especiales!"];
			}
		}
	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/
	public static function ctrMostrarClientes($item, $valor){
		$tabla = "clientes";
		$respuesta = ModeloClientes::mdlMostrarClientes($tabla, $item, $valor);
		return $respuesta;
	}
	/*=============================================
	EDITAR CLIENTE
	=============================================*/
	public static function ctrEditarCliente(){
		if(isset($_POST["editarCliente"])){
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCliente"]) &&
			   preg_match('/^[0-9]+$/', $_POST["editarDocumentoId"]) &&
			   preg_match('/^[()\-0-9 ]+$/', $_POST["editarTelefono"])
			   ){

			   	$tabla = "clientes";
			   	$datos = array("id"=>$_POST["idCliente"],
			   				   "nombre"=>$_POST["editarCliente"],
					           "documento"=>$_POST["editarDocumentoId"],
					           "email"=>$_POST["editarEmail"],
					           "telefono"=>$_POST["editarTelefono"],
					           "direccion"=>$_POST["editarDireccion"],
							   "ciudad"=>$_POST["editarCiudad"]
					           );
			   	$respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);
			   	if($respuesta == "ok"){
					echo'<script>
					swal({
						  type: "success",
						  title: "El cliente ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {
									window.location = "clientes";
									}
								})
					</script>';
				}
			}else{
				echo'<script>
					swal({
						  type: "error",
						  title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {
							window.location = "clientes";
							}
						})
			  	</script>';
			}
		}
	}
	/*=============================================
	ELIMINAR CLIENTE
	=============================================*/
	public static function ctrEliminarCliente(){
		if(isset($_GET["idCliente"])){
			$tabla ="clientes";
			$datos = $_GET["idCliente"];
			$respuesta = ModeloClientes::mdlEliminarCliente($tabla, $datos);
			if($respuesta == "ok"){
				echo'<script>
				swal({
					  type: "success",
					  title: "El cliente ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then(function(result){
								if (result.value) {
								window.location = "clientes";
								}
							})
				</script>';
			}		
		}
	}
}
