<?php

class ControladorSubCategorias{
    /*=============================================
    MOSTRAR CATEGORIAS
	=============================================*/

	public static function ctrMostrarSubCategorias($item, $valor){
		$tabla = "subcategorias";
		$respuesta = ModeloSubCategorias::mdlMostrarSubCategorias($tabla, $item, $valor);
		
		return $respuesta;
	
	}

	/*=============================================
	CREAR CATEGORIAS
	=============================================*/

	public static function ctrCrearSubCategoria(){

		if(isset($_POST["nuevaSubCategoria"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaSubCategoria"])){

				$tabla = "subcategorias";
				$datos = array("nombre" => $_POST["nuevaSubCategoria"],
								"categoria_id" => $_POST["nuevaCategoria"]);

				$respuesta = ModeloSubCategorias::mdlIngresarSubCategoria($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>
					swal({
						  type: "success",
						  title: "La subcategoría ha sido guardada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "subcategorias";

									}
								})
					</script>';
				}
			}else{

				echo'<script>
					swal({
						  type: "error",
						  title: "¡La subcategoría no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "subcategorias";

							}
						})
			  	</script>';

			}
		}
	}

	/*=============================================
	EDITAR SUBCATEGORIA
	=============================================*/

	public static function ctrEditarSubCategoria(){

		if(isset($_POST["editarSubCategoria"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarSubCategoria"])){

				$tabla = "subcategorias";
				$datos = array("id" => $_POST["idSubCategoria"],
								"nombre" => $_POST["editarSubCategoria"]
							);

				$respuesta = ModeloSubCategorias::mdlEditarSubCategoria($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>
					swal({
						  type: "success",
						  title: "La subcategoría ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "subcategorias";

									}
								})

					</script>';

				}


			}else{

				echo'<script>
					swal({
						  type: "error",
						  title: "¡La subcategoría no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "subcategorias";

							}
						})
			  	</script>';

			}
		}
	}

	/*=============================================
	BORRAR CATEGORIA
	=============================================*/

	public static function ctrBorrarSubCategoria(){

		if(isset($_GET["idSubCategoria"])){

			$tabla ="subcategorias";
			$datos = $_GET["idSubCategoria"];

			$respuesta = ModeloSubCategorias::mdlBorrarSubCategoria($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>
					swal({
						  type: "success",
						  title: "La subcategoría ha sido borrada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "subcategorias";

									}
								})

					</script>';
			}
		}		
	}

}