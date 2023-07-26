<?php

require_once "../controladores/movimientos.controlador.php";
require_once "../modelos/movimientos.modelo.php";

require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";

class AjaxMovimientos{

  /*=============================================
  GENERAR CÓDIGO A PARTIR DE ID CATEGORIA
  =============================================*/
  public $idCategoria;

  public function ajaxCrearCodigoMovimiento(){

  	$item = "id_categoria";
  	$valor = $this->idCategoria;
    $orden = "id";

  	$respuesta = ControladorMovimientos::ctrMostrarMovimientos($item, $valor, $orden);

  	echo json_encode($respuesta);

  }


  /*=============================================
  EDITAR PRODUCTO
  =============================================*/ 

  public $idMovimiento;
  public $traerMovimientos;
  public $nombreMovimiento;

  public function ajaxEditarMovimiento(){

    if($this->traerMovimientos == "ok"){

      $item = null;
      $valor = null;
      $orden = "id";

      $respuesta = ControladorMovimientos::ctrMostrarMovimientos($item, $valor,
        $orden);

      echo json_encode($respuesta);


    }else if($this->nombreMovimiento != ""){

      $item = "descripcion";
      $valor = $this->nombreMovimiento;
      $orden = "id";

      $respuesta = ControladorMovimientos::ctrMostrarMovimientos($item, $valor,
        $orden);

      echo json_encode($respuesta);

    }else{

      $item = "id";
      $valor = $this->idMovimiento;
      $orden = "id";

      $respuesta = ControladorMovimientos::ctrMostrarMovimientos($item, $valor,
        $orden);

      echo json_encode($respuesta);

    }

  }

}


/*=============================================
GENERAR CÓDIGO A PARTIR DE ID CATEGORIA
=============================================*/	

if(isset($_POST["idCategoria"])){

	$codigoMovimiento = new AjaxMovimientos();
	$codigoMovimiento -> idCategoria = $_POST["idCategoria"];
	$codigoMovimiento -> ajaxCrearCodigoMovimiento();

}
/*=============================================
EDITAR PRODUCTO
=============================================*/ 

if(isset($_POST["idMovimiento"])){

  $editarMovimiento = new AjaxMovimientos();
  $editarMovimiento -> idMovimiento = $_POST["idMovimiento"];
  $editarMovimiento -> ajaxEditarMovimiento();

}

/*=============================================
TRAER PRODUCTO
=============================================*/ 

if(isset($_POST["traerMovimientos"])){

  $traerMovimientos = new AjaxMovimientos();
  $traerMovimientos -> traerMovimientos = $_POST["traerMovimientos"];
  $traerMovimientos -> ajaxEditarMovimiento();

}

/*=============================================
TRAER PRODUCTO
=============================================*/ 

if(isset($_POST["nombreMovimiento"])){

  $traerMovimientos = new AjaxMovimientos();
  $traerMovimientos -> nombreMovimiento = $_POST["nombreMovimiento"];
  $traerMovimientos -> ajaxEditarMovimiento();

}