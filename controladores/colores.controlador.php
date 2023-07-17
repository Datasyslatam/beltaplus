<?php

class ControladorColores{
    /*=============================================
    MOSTRAR COLORES
	=============================================*/

    public static function ctrMostrarColores($item, $valor){
		$tabla = "colores";
		$respuesta = ModeloColores::mdlMostrarColores($tabla, $item, $valor);

		return $respuesta;
	
	}

}