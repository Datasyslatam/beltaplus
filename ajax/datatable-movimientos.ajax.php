<?php

require_once "../controladores/movimientos.controlador.php";
require_once "../modelos/movimientos.modelo.php";

require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";


class TablaMovimientos{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 

	public function mostrarTablaMovimientos(){

		$item = null;
    	$valor = null;
    	$orden = "id";

  		$movimientos = ControladorMovimientos::ctrMostrarMovimientos($item, $valor, $orden);	

  		if(count($movimientos) == 0){

  			echo '{"data": []}';

		  	return;
  		}
		
  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($movimientos); $i++){

		  	/*=============================================
 	 		TRAEMOS LA IMAGEN
  			=============================================*/ 

		  	$imagen = "<img src='".$movimientos[$i]["imagen"]."' width='40px'>";

		  	/*=============================================
 	 		TRAEMOS LA CATEGORÍA
  			=============================================*/ 

		  	$item = "id";
		  	$valor = $movimientos[$i]["id_categoria"];

		  	$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

		  	/*=============================================
 	 		STOCK
  			=============================================*/ 

  			if($movimientos[$i]["stock"] <= 10){

  				$stock = "<button class='btn btn-danger'>".$movimientos[$i]["stock"]."</button>";

  			}else if($movimientos[$i]["stock"] > 11 && $movimientos[$i]["stock"] <= 15){

  				$stock = "<button class='btn btn-warning'>".$movimientos[$i]["stock"]."</button>";

  			}else{

  				$stock = "<button class='btn btn-success'>".$movimientos[$i]["stock"]."</button>";

  			}

		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/ 

  			if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Especial"){

  				$botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarMovimiento' idMovimiento='".$movimientos[$i]["id"]."' data-toggle='modal' data-target='#modalEditarMovimiento'><i class='fa fa-pencil'></i></button></div>"; 

  			}else{

  				 $botones =  "<div class='btn-group'><button class='btn btn-success btnIngresoMovimiento' idMovimiento='".$movimientos[$i]["id"]."' data-toggle='modal' data-target='#modalIngresoMovimiento'><i class='fa fa-plus' aria-hidden='true'></i></button><button class='btn btn-danger btnSalidaMovimiento' idMovimiento='".$movimientos[$i]["id"]."' data-toggle='modal' data-target='#modalSalidaMovimiento'><i class='fa fa-minus' aria-hidden='true'></i></button><a href='index.php?ruta=kardex-producto&idProducto=".$movimientos[$i]["id"]."'><button class='btn btn-primary' idMovimiento='".$movimientos[$i]["id"]."'><i class='fa fa-eye' aria-hidden='true'></i></button></a></div>"; 

  			}

	    /*"'.$movimientos[$i]["precio_compra"].'",
			      "'.$movimientos[$i]["precio_venta"].'",*/	 
		  	$datosJson .='[
			      "'.($i+1).'",
			      "'.$imagen.'",
			      "'.$movimientos[$i]["codigo"].'",
			      "'.$movimientos[$i]["descripcion"].'",
			      "'.$categorias["categoria"].'",
			      "'.$stock.'",
			      "'.$movimientos[$i]["fecha"].'",
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
$activarMovimientos = new TablaMovimientos();
$activarMovimientos -> mostrarTablaMovimientos();

