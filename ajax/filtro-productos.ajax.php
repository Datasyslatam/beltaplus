<?php

require_once "../controladores/filtro-productos.controlador.php";
require_once "../modelos/filtro-productos.modelo.php";

class AjaxFiltroProductos
{

    public $categoria_id;
    public $subcategoria_id;
    public $color_id;

    private $datos;

    function setData()
    {
        $this->datos = [
            "idcategoria" => $this->categoria_id,
            "idsubcategoria" => $this->subcategoria_id,
            "idcolor" => $this->color_id,
        ];
    }

    public function filtrarTallas() {

        $respuesta = ControladorFiltroProductos::ctrMostrarFiltroProductosTallas($this->datos);
        return $respuesta;
    }
    
    public function filtrarPrecios() {

        $respuesta = ControladorFiltroProductos::ctrMostrarFiltroProductosPrecios($this->datos);
        return $respuesta;
    }

    public function filtrarProductosVentas(){
        $respuesta = ControladorFiltroProductos::ctrMostrarFiltroProductosVentas($this->datos);
        return $respuesta;
    }
}

/*=====================================
    FILTRADO DINAMICO PRODUCTOS
======================================= */
if (isset($_POST['idCategoria'])) {
    $filtrar = new AjaxFiltroProductos();
    $filtrar->categoria_id = $_POST['idCategoria'];
    $filtrar->subcategoria_id = $_POST['idSubcategoria'];
    $filtrar->color_id = $_POST['idcolorres'];

    $filtrar->setData();

    $respuesta_tallas = $filtrar->filtrarTallas();
    $respuesta_precios = $filtrar->filtrarPrecios();

    $array_respuestas = [
        "tallas" => $respuesta_tallas,
        "precios" => $respuesta_precios
    ];

    echo json_encode($array_respuestas);
}

/*=====================================
    FILTRADO DINAMICO VENTAS
======================================= */
if (isset($_POST['id_categoria'])) {
    $filtrar = new AjaxFiltroProductos();
    $filtrar->categoria_id = $_POST['id_categoria'];
    $filtrar->subcategoria_id = $_POST['id_subcategoria'];
    $filtrar->color_id = $_POST['id_calor'];

    $filtrar->setData();

    $respuesta_productos = $filtrar->filtrarProductosVentas();

    echo json_encode($respuesta_productos);
}
