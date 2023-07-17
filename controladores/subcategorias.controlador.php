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
}