<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";

require_once "../controladores/subcategorias.controlador.php";
require_once "../modelos/subcategorias.modelo.php";

require_once "../controladores/colores.controlador.php";
require_once "../modelos/colores.modelo.php";

require_once "../controladores/tallas.controlador.php";
require_once "../modelos/tallas.modelo.php";

class TablaProductosVentas{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 

	public function mostrarTablaProductosVentas(){

		$item = null;
    	$valor = null;
    	$orden = "id";

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
 		
  		if(count($productos) == 0){

  			echo '{"data": []}';

		  	return;
  		}	
		
  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($productos); $i++){

		  	/*=============================================
 	 		TRAEMOS LA IMAGEN
  			=============================================*/ 

		  	//$imagen = "<img src='".$productos[$i]["imagen"]."' width='40px'>";

			/*=============================================
 	 		TRAEMOS LA CATEGORÍA
  			=============================================*/ 
		  	$item = "id";
		  	$valor = $productos[$i]["id_categoria"];
		  	$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
			$nombre_categoria = "";
			if($categorias){
				$nombre_categoria = $categorias["categoria"];
			}

			/*=============================================
 	 		TRAEMOS LA SUBCATEGORÍA
  			=============================================*/ 
			  $item = "id";
		  	$valor = $productos[$i]["id_subcategoria"];
			$nomb_subcategoria = "";

		  	$subcategorias = ControladorSubCategorias::ctrMostrarSubCategorias($item, $valor, false);
			/* var_export($subcategorias["nombre"]." " . $productos[$i]['codigo']); */
			if ($subcategorias){
				$nomb_subcategoria = $subcategorias["nombre"];
			}

			/*=============================================
 	 		TRAEMOS EL COLOR
  			=============================================*/ 
			$item = "id";
		  	$valor = $productos[$i]["id_color"];
			$nomb_color = "";

		  	$colores = ControladorColores::ctrMostrarColores($item, $valor);

			if ($colores){
				$nomb_color = $colores["color"];
			}

			$item = "id";
		  	$valor = $productos[$i]["id_talla"];
			$nomb_talla = "";

		  	$tallas = ControladorTallas::ctrMostrarTallas($item, $valor);

			if ($tallas){
				$nomb_talla = $tallas["talla"];
			}

		  	/*=============================================
 	 		STOCK
  			=============================================*/ 

  			if($productos[$i]["stock"] <= 10){

  				$stock = "<button class='btn btn-danger'>".$productos[$i]["stock"]."</button>";

  			}else if($productos[$i]["stock"] > 11 && $productos[$i]["stock"] <= 15){

  				$stock = "<button class='btn btn-warning'>".$productos[$i]["stock"]."</button>";

  			}else{

  				$stock = "<button class='btn btn-success'>".$productos[$i]["stock"]."</button>";

  			}

		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/ 

		  	$botones =  "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='".$productos[$i]["id"]."'>Agregar</button></div>"; 

		  	$datosJson .='[
			      "'.$productos[$i]["codigo"].'",
			      "'.$nombre_categoria.'",
			      "'.$nomb_subcategoria.'",
			      "'.$nomb_color.'",
			      "'.$nomb_talla.'",
			      "'.$stock.'",
			      "'.$botones.'"
			    ],';
		  	/* $datosJson .='[
			      "'.($i+1).'",
			      "'.$imagen.'",
			      "'.$productos[$i]["codigo"].'",
			      "'.$productos[$i]["descripcion"].'",
			      "'.$stock.'",
			      "'.$botones.'"
			    ],'; */

		  }

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   '] 

		 }';
		
		echo $datosJson;


	}


}

/*=============================================
ACTIVAR TABLA DE PRODUCTOS
=============================================*/ 
$activarProductosVentas = new TablaProductosVentas();
$activarProductosVentas -> mostrarTablaProductosVentas();

