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
}