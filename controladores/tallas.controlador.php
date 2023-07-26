<?php

class ControladorTallas{

    /*=============================================
    MOSTRAR COLORES
	=============================================*/
    
    public static function ctrMostrarTallas($item, $valor){
		$tabla = "tallas";
		$respuesta = ModeloTallas::mdlMostrarTallas($tabla, $item, $valor);

		return $respuesta;	
	}

	/*=============================================
	CREAR TALLA
	=============================================*/

	public static function ctrCrearTalla(){

		if(isset($_POST["nuevaTalla"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaTalla"])){

				$tabla = "tallas";
				$datos = $_POST["nuevaTalla"];
				$respuesta = ModeloTallas::mdlIngresarTalla($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "La Talla ha sido guardada correctamente.!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "tallas";

									}
								})

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡La Talla no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "tallas";

							}
						})
			  	</script>';

			}
		}
	}

	/*=============================================
	EDITAR TALLA
	=============================================*/

	public static function ctrEditarTalla(){

		if(isset($_POST["editarTalla"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarTalla"])){

				$tabla = "tallas";

				$datos = array("id"=>$_POST["idTalla"],
								"talla"=>$_POST["editarTalla"]
							   );

				$respuesta = ModeloTallas::mdlEditarTalla($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "La talla ha sido actualizada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "tallas";

									}
								})

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El nombre de la talla no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "categorias";

							}
						})

			  	</script>';
			}
		}
	}

	/*=============================================
	BORRAR TALLA
	=============================================*/

	public static function ctrBorrarTalla(){

		if(isset($_GET["idTalla"])){

			$tabla ="tallas";
			$datos = $_GET["idTalla"];

			$respuesta = ModeloTallas::mdlBorrarTalla($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  type: "success",
						  title: "La talla ha sido borrada correctamente.!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "tallas";

									}
								})

					</script>';
			}
		}
	}
} // Fin de la Clase