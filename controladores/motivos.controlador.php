<?php

class ControladorMotivos{

	/*=============================================
	CREAR MOTIVOS
	=============================================*/

	public static function ctrCrearMotivo(){

		if(isset($_POST["nuevaMotivo"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaMotivo"])){

				$tabla = "motivos";

				$datos = $_POST["nuevaMotivo"];

				$respuesta = ModeloMotivos::mdlIngresarMotivo($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El motivo ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "motivos";

									}
								})

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El motivo no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "motivos";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	MOSTRAR MOTIVOS
	=============================================*/

	public static function ctrMostrarMotivos($item, $valor){
		$tabla = "motivos";
		$respuesta = ModeloMotivos::mdlMostrarMotivos($tabla, $item, $valor);

		return $respuesta;
	
	}

	/*=============================================
	EDITAR MOTIVO
	=============================================*/

	public static function ctrEditarMotivo(){

		if(isset($_POST["editarMotivo"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarMotivo"])){

				$tabla = "motivos";

				$datos = array("motivo"=>$_POST["editarMotivo"],
							   "id"=>$_POST["idMotivo"]);

				$respuesta = ModeloMotivos::mdlEditarMotivo($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El motivo ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "motivos";

									}
								})

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El motivo no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "motivos";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	BORRAR MOTIVO
	=============================================*/

	public static function ctrBorrarMotivo(){

		if(isset($_GET["idMotivo"])){

			$tabla ="Motivos";
			$datos = $_GET["idMotivo"];

			$respuesta = ModeloMotivos::mdlBorrarMotivo($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  type: "success",
						  title: "El motivo ha sido borrada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "motivos";

									}
								})

					</script>';
			}
		}
		
	}
}
