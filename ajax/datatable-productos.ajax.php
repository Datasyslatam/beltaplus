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

class TablaProductos{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 

	public function mostrarTablaProductos(){

		$item = null;
    	$valor = null;
    	$orden = "codigo";

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);	

		//var_export($productos);

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

		  	$imagen = "<img src='".$productos[$i]["imagen"]."' width='40px'>";

		  	/*=============================================
 	 		TRAEMOS LA CATEGORÍA
  			=============================================*/ 
		  	$item = "id";
		  	$valor = $productos[$i]["id_categoria"];

		  	$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
			
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
			$cod_color = "";		// Para pintar filtro de Botones con su nombnre de color

		  	$colores = ControladorColores::ctrMostrarColores($item, $valor);

			if ($colores){
				$nomb_color = $colores["color"];
				$cod_color = $colores["cod_color"];	// Para pintar filtro de Botones con su nombnre de color
			}

			/*=============================================
 	 		TRAEMOS LA TALLA
  			=============================================*/ 
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

  			if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Especial"){

  				$botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["id"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button></div>"; 

  			}else{

  				 $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["id"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarProducto' idProducto='".$productos[$i]["id"]."' codigo='".$productos[$i]["codigo"]."' imagen='".$productos[$i]["imagen"]."'><i class='fa fa-times'></i></button></div>"; 

  			}

		 
		  	$datosJson .='[
			      "'.($i+1).'",
			      "'.$productos[$i]["codigo"].'",
			      "'.$categorias["categoria"].'",
				  "'.$nomb_subcategoria.'",
				  "'.$nomb_color.'",
				  "'.$nomb_talla.'",
			      "'.$stock.'",
			      "'. "$" . number_format($productos[$i]["precio_compra"]) .'",
				  "'. "$" . number_format($productos[$i]["precio_venta"]) .'",
			      "'.$productos[$i]["fecha"].'",
			      "'.$botones.'"
			    ],';

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
$activarProductos = new TablaProductos();
$activarProductos -> mostrarTablaProductos();

