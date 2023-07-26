<?php

class ControladorFiltroProductos{
    /*=============================================
    MOSTRAR CATEGORIAS
	=============================================*/

	public static function ctrMostrarFiltroProductosTallas($data){

        $condiciones = "";
        $color = false;
        if(!empty($data["idcategoria"])){
            $condiciones .= "WHERE p.id_categoria = ".$data["idcategoria"];
        }
        
        if(!empty($data["idsubcategoria"])){
            $condiciones .= " AND p.id_subcategoria = ".$data["idsubcategoria"];
        }
        
        if(!empty($data["idcolor"])){
            $condiciones .= " AND p.id_color = ".$data["idcolor"];
            $color = true;
        }

		$respuesta = ModeloFiltroProductos::mdlMostrarFiltroProductosTallas($condiciones, $color);
		return $respuesta;
	
	}
	
    public static function ctrMostrarFiltroProductosPrecios($data){

        $condiciones = "";
        if(!empty($data["idcategoria"])){
            $condiciones .= "WHERE p.id_categoria = ".$data["idcategoria"];
        }
        
        if(!empty($data["idsubcategoria"])){
            $condiciones .= " AND p.id_subcategoria = ".$data["idsubcategoria"];
        }
        
        if(!empty($data["idcolor"])){
            $condiciones .= " AND p.id_color = ".$data["idcolor"];
            $color = true;
        }

		$respuesta = ModeloFiltroProductos::mdlMostrarFiltroProductosPrecios($condiciones);
		return $respuesta;
	
	}
}