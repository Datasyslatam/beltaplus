<?php

require_once "conexion.php";

class ModeloFiltroProductos{
    /*=============================================
	MOSTRAR CATEGORIAS
	=============================================*/

	public static function mdlMostrarFiltroProductosTallas($clausula, $filtroColor = false){

        $stmt = Conexion::conectar()->prepare("SELECT DISTINCT(c.color), c.id 
        FROM productos p 
        INNER JOIN colores c ON c.id = p.id_color $clausula");
        $stmt -> execute();
        
        $resultado = $stmt -> fetchAll();

        $tallas = ModeloFiltroProductos::getTallas();
        
        $i = 1;
        $datos_tallas = [];

        foreach ($resultado as $key => $value) {
            $id = $value["id"];
            $color = $value["color"];

            $clausula_color = $clausula;
            if(!$filtroColor){
                $clausula_color .= empty($clausula) ? "WHERE" : " AND";
                $clausula_color .= " p.id_color = ".$id;
            }

            $fila = [$i,$id,$color];

            foreach ($tallas as $value2){
                $id_talla = $value2["id"];
                $clausula_talla = $clausula_color." AND p.id_talla = ".$id_talla;

                $stmt = Conexion::conectar()->prepare("SELECT SUM(stock) AS conteo FROM productos p $clausula_talla");
                $stmt -> execute();

                
                $conteo = $stmt -> fetchAll();

                $num_prod = 0;
                if ($conteo){
                    $num = $conteo[0]["conteo"];
                    $num_prod = $num == "" ? 0 : $num;
                }
                
                array_push($fila, $num_prod);
            }
            
            array_push($datos_tallas, $fila);

            $i++;
        }

        return $datos_tallas;

	}


    public static function mdlMostrarFiltroProductosPrecios($clausula){

        $stmt = Conexion::conectar()->prepare("SELECT p.codigo, p.descripcion, t.talla, co.color, ca.categoria, 
                        p.stock as cantidad, COALESCE(p.h_salida_cantidad, 0) as h_salida_cantidad, 
                        COALESCE(p.inventario_final,0) as inventario_final, p.precio_venta, 
                        p.precio_compra
                        FROM productos p
                        INNER JOIN tallas t ON  t.id = p.id_talla
                        INNER JOIN colores co ON co.id = p. id_color
                        INNER JOIN categorias ca on ca.id = p.id_categoria
                        $clausula");

        $stmt -> execute();
        
        $resultado = $stmt -> fetchAll();
        return $resultado;
        
    }

    public static function getTallas(){
        $stmt = Conexion::conectar()->prepare("SELECT id FROM tallas");
        $stmt -> execute();
        
        $tallas = $stmt -> fetchAll();

        return $tallas;
    }


}