<?php

class ControladorMovimientos{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/
	public static function ctrMostrarMovimientos($item, $valor, $orden){
		$tabla = "productos";
		$respuesta = ModeloMovimientos::mdlMostrarMovimientos($tabla, $item, $valor, $orden);
		return $respuesta;
	}

	/*=============================================
	MOSTRAR KARDEX
	=============================================*/
	public static function ctrMostrarkardex($item, $valor){
		$tabla = "historial";
		$respuesta = ModeloMovimientos::mdlMostrarMovimientosKardex($tabla, $item, $valor);
		return $respuesta;
	}

	/*=============================================
	INGRESO PRODUCTO
	=============================================*/
	public static function ctrIngresoMovimiento(){
		if(isset($_POST["ingresoStock"])){
			if(isset($_POST["ingresoStock"])){
				$tabla = "productos";

				$datos = array("ingresoMotivo" => $_POST["ingresoMotivo"],
							   "ingresoStock" => $_POST["ingresoStock"],
							   "id" => $_POST["ingresoId"]);

				$respuesta = ModeloMovimientos::mdlIngresoMovimiento($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

						swal({
							  type: "success",
							  title: "El movimiento ha sido ingresado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "movimientos";
										}
									})

						</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El movimiento no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "movimientos";

							}
						})

			  	</script>';
			}
		}

	}
	/*=============================================
	SALIDA PRODUCTO
	=============================================*/
	public static function ctrSalidaMovimiento(){
		if(isset($_POST["salidaStock"])){
			if(isset($_POST["salidaStock"])){
				$tabla = "productos";
				$datos = array("salidaMotivo" => $_POST["salidaMotivo"],
							   "salidaStock" => $_POST["salidaStock"],
							   "id" => $_POST["salidaId"]);

				$respuesta = ModeloMovimientos::mdlSalidaMovimiento($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

						swal({
							  type: "success",
							  title: "El movimiento ha sido ingresado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {
										window.location = "movimientos";
										}
									})

						</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El movimiento no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "movimientos";

							}
						})

			  	</script>';
			}
		}

	}
	/*=============================================
	BORRAR PRODUCTO
	=============================================*/
	public static function ctrEliminarMovimiento(){

		if(isset($_GET["idMovimiento"])){

			$tabla ="productos";
			$datos = $_GET["idMovimiento"];

			if($_GET["imagen"] != "" && $_GET["imagen"] != "vistas/img/productos/default/anonymous.png"){

				unlink($_GET["imagen"]);
				rmdir('vistas/img/productos/'.$_GET["codigo"]);

			}

			$respuesta = ModeloMovimientos::mdlEliminarMovimiento($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El movimiento ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "movimientos";

								}
							})

				</script>';

			}		
		}


	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/

	public static function ctrMostrarSumaVentas(){

		$tabla = "productos";

		$respuesta = ModeloMovimientos::mdlMostrarSumaVentas($tabla);

		return $respuesta;

	}


}