<?php

require_once "../controladores/subcategorias.controlador.php";
require_once "../modelos/subcategorias.modelo.php";

class AjaxSubcategoria{
    /*=====================================
        FILTAR LA SUBCATEGORIAS
    ======================================= */

    public $categoria_id;

    public function ajaxFiltrarSubCategorias(){                 // Filtra Subcategorias segun Categoria seleccionada (Nvos Productos)
        $item = "categoria_id";
        $valor =  $this->categoria_id;

        $respuesta = ControladorSubCategorias::ctrMostrarSubCategorias($item, $valor);
        
        echo json_encode($respuesta);

    }
}

/*=====================================
    FILTAR LA SUBCATEGORIAS
======================================= */
if(isset($_POST['idCategoria'])){
    $filtrar = new AjaxSubcategoria();
    $filtrar->categoria_id = $_POST['idCategoria'];
    $filtrar->ajaxFiltrarSubCategorias();
}